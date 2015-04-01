<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\VolumeHoraire;
use Application\Entity\Db\TypeValidation;
use Application\Service\TypeValidation as TypeValidationService;
use Application\Service\VolumeHoraire as VolumeHoraireService;
use Application\Traits\StructureAwareTrait;
use Application\Traits\TypeVolumeHoraireAwareTrait;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Recherche si les enseignements d'un intervenant (d'un type d'heures à spécifier)
 * au sein d'une structure ont été validés.
 *
 * Cette règle renvoit :
 * - <code>true</code> si tous les volumes horaires ont été validés ;
 * - <code>false</code> si des volumes horaires n'ont pas encore été validés.
 *
 * Dans le cas d'une recherche partielle, cette règle renvoit :
 * - <code>true</code> si tout ou partie des volumes horaires ont été validés ;
 * - <code>false</code> si aucun volume horaire n'a été validé.
 *
 * NB: 2 getters permettent de connaître les services et volumes horaires
 * déjà validés et ceux non encore validés.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ServiceValideRule extends AbstractIntervenantRule
{
    use TypeVolumeHoraireAwareTrait;
    use StructureAwareTrait;

    const MESSAGE_AUCUNE     = 'messageAucune';
    const MESSAGE_INCOMPLETE = 'messageIncomplete';
    const MESSAGE_COMPLETE   = 'messageComplete';
    const MESSAGE_PARTIELLE  = 'messagePartielle';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_AUCUNE     => "Les enseignements de %value% n'ont fait l'objet d'aucune validation.",
        self::MESSAGE_INCOMPLETE => "Tous les volumes horaires d'enseignement de %value% n'ont pas été validés.",
        self::MESSAGE_COMPLETE   => "Tous les volumes horaires d'enseignement de %value% ont été validés.",
        self::MESSAGE_PARTIELLE  => "Les enseignements de %value% ont été validés PARTIELLEMENT.",
    ];

    /**
     * Exécute la règle métier.
     *
     * @return array [ integer => [ 'id' => {id} ] ]
     */
    public function execute()
    {
        $this->message(null);

        $qb = $this->getQueryBuilder();

        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
//            $result = $qb->getQuery()->getScalarResult();
//
//            if (!$result) {
//                $this->message(self::MESSAGE_AUCUNE);
//            }
            $result = $this->executeForIntervenant();

            return $this->normalizeResult($result);
        }

        /**
         * Recherche des intervenants répondant à la règle
         */

        $result = $qb->getQuery()->getScalarResult();

        return $this->normalizeResult($result);
    }

    protected function executeForIntervenant()
    {
        $vhService = $this->getServiceVolumeHoraire();

        $qb = $vhService->finderByIntervenant($this->getIntervenant());
        $qb = $vhService->finderByTypeVolumeHoraire($this->getTypeVolumeHoraire(), $qb);
        // NB: pas de filtre sur le type de validation ici, car on veut collecter les VH validés et non validés plus bas...
        if ($this->getStructure()) {
            $vhService->finderByStructureIntervention($this->getStructure(), $qb);
        }

        $volumesHoraires = $qb->getQuery()->getResult();

        $this->volumesHorairesNonValides = [];
        $this->volumesHorairesValides    = [];

        foreach ($volumesHoraires as $vh) { /* @var $vh VolumeHoraire */
            if (!count($vh->getValidation($this->getTypeValidationService()))) {
                $this->volumesHorairesNonValides[] = $vh;
            }
            else {
                $this->volumesHorairesValides[] = $vh;
            }
        }

        $info = sprintf("%s%s",
                $this->getIntervenant(),
                $this->getStructure() ? sprintf(" au sein de la structure &laquo; %s &raquo;", $this->getStructure()) : null);

        if (!count($this->volumesHorairesValides)) {
            $this->message(self::MESSAGE_AUCUNE, $info);
            return [];
        }

        if (!$this->memePartiellement && count($this->volumesHorairesNonValides)) {
            $this->message(self::MESSAGE_INCOMPLETE, $info);
            return [];
        }

//        if (!count($this->volumesHorairesNonValides)) {
//            $this->message(self::MESSAGE_COMPLETE, $info);
//        }
//        else {
//            $this->message(self::MESSAGE_PARTIELLE, $info);
//        }

        return [0 => ['id' => $this->getIntervenant()->getId()]];
    }

    public function isRelevant()
    {
        if ($this->getIntervenant()) {
            return $this->getIntervenant()->getStatut()->getPeutSaisirService();
        }

        return true;
    }

    /**
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $em = $this->getServiceIntervenant()->getEntityManager();
        $qb = $em->getRepository('Application\Entity\Db\Intervenant')->createQueryBuilder("i")
                ->select("i.id")
                ->distinct()
                ->join("i.service", 's')
                ->join("s.elementPedagogique", "ep")
                ->join("ep.structure", "strEns")
                ->join("s.volumeHoraire", 'vh')
                ->join("vh.typeVolumeHoraire", "tvh", \Doctrine\ORM\Query\Expr\Join::WITH, "tvh = :tvh")
                ->join("vh.validation", "v", Join::WITH, "v.typeValidation = " . $this->getTypeValidationService()->getId())
                ->join("v.typeValidation", "tv")
                ->setParameter('tvh', $this->getTypeVolumeHoraire());

        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }

        if ($this->getStructure()) {
            $qb->andWhere("strEns = " . $this->getStructure()->getId());
        }

        return $qb;
    }

    /**
     * Flag indiquant si l'on se satisfait d'une validation partielle des services.
     * Autrement dit, avec ce flag à <code>true</code>, les services seront considérés comme validés
     * (i.e. cette règle retournera <code>true</code>) si au moins un volume horaire est validé.
     *
     * @var boolean
     */
    private $memePartiellement = true;

    /**
     *
     * @param boolean $memePartiellement
     * @return ServiceValideRule
     */
    public function setMemePartiellement($memePartiellement = true)
    {
        $this->memePartiellement = $memePartiellement;
        return $this;
    }

    private $servicesValides;

    /**
     * Retourne les services déjà validés.
     *
     * @return array|null
     */
    public function getServicesValides()
    {
        if (null === $this->servicesValides) {
            $this->servicesValides = [];
            foreach ($this->getVolumesHorairesValides() as $vh) { /* @var $vh VolumeHoraire */
                $this->servicesValides[$vh->getService()->getId()] = $vh->getService();
            }
        }
        return $this->servicesValides;
    }

    private $servicesNonValides;

    /**
     * Retourne les services non encore validés.
     *
     * @return array|null
     */
    public function getServicesNonValides()
    {
        if (null === $this->servicesNonValides) {
            $this->servicesNonValides = [];
            foreach ($this->getVolumesHorairesNonValides() as $vh) { /* @var $vh VolumeHoraire */
                $this->servicesNonValides[$vh->getService()->getId()] = $vh->getService();
            }
        }
        return $this->servicesNonValides;
    }

    private $volumesHorairesValides;

    /**
     * Retourne les volumes horaires déjà validés.
     *
     * @return array|null
     */
    public function getVolumesHorairesValides()
    {
        return $this->volumesHorairesValides ?: [];
    }

    private $volumesHorairesNonValides;

    /**
     * Retourne les volumes horaires non encore validés.
     *
     * @return array|null
     */
    public function getVolumesHorairesNonValides()
    {
        return $this->volumesHorairesNonValides ?: [];
    }

    /**
     * Retourne le service VolumeHoraire.
     *
     * @return VolumeHoraireService
     */
    public function getServiceVolumeHoraire()
    {
        return $this->getServiceLocator()->get('ApplicationVolumeHoraire');
    }

    /**
     * @return TypeValidation
     */
    private function getTypeValidationService()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_ENSEIGNEMENT);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();

        return $typeValidation;
    }

    /**
     *
     * @return TypeValidationService
     */
    private function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    }
}