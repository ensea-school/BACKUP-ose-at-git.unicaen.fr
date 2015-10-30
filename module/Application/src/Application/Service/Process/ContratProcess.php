<?php

namespace Application\Service\Process;

use Application\Entity\Db\TypeContrat;
use Application\Entity\Db\TypeValidation;
use Application\Rule\Intervenant\PeutCreerAvenantRule;
use Application\Rule\Intervenant\PeutCreerContratInitialRule;
use Application\Service\AbstractService;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Common\Exception\RuntimeException;
use Application\Entity\Db\Contrat;

/**
 * Workflow de création des contrats et avenants.
 *
 * @method \Application\Entity\Db\Intervenant getIntervenant() Description
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratProcess extends AbstractService
{
    use IntervenantAwareTrait,
        \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\ContratAwareTrait,
        \Application\Service\Traits\ServiceAPayerAwareTrait,
        \Application\Service\Traits\TypeVolumeHoraireAwareTrait,
        \Application\Service\Traits\ServiceAwareTrait,
        \Application\Service\Traits\EtatVolumeHoraireAwareTrait
    ;

    /**
     *
     * @return \Application\Service\Process\ContratProcess
     */
    public function creer()
    {
        if (($peutCreerContrat = $this->getPeutCreerContratInitialRule()->execute())) {
            $servicesDispos        = $this->getServicesDisposPourContrat();
            $volumesHorairesDispos = $this->getVolumesHorairesDisposPourContrat();

            if (!count($volumesHorairesDispos)) {
                throw new RuntimeException("Anomalie : aucun volume horaire n'a été trouvé pour créer le contrat.");
            }

            $this->creerContrat($volumesHorairesDispos);

            $this->messages[] = sprintf("Contrat de %s enregistré avec succès.", $this->getIntervenant());
        }
        elseif (($peutCreerAvenant = $this->getPeutCreerAvenantRule()->execute())) {
            $servicesDispos        = $this->getServicesDisposPourAvenant();
            $volumesHorairesDispos = $this->getVolumesHorairesDisposPourAvenant();

            if (!count($volumesHorairesDispos)) {
                throw new RuntimeException("Anomalie : aucun volume horaire n'a été trouvé pour créer l'avenant.");
            }

            $this->creerAvenant($volumesHorairesDispos);

            $this->messages[] = sprintf("Avenant de %s enregistré avec succès.", $this->getIntervenant());
        }

        return $this;
    }

    /**
     * Crée un projet de contrat, c'est à dire un contrat non encore validé.
     *
     * @return Contrat Contrat créé
     * @throws RuntimeException Si aucun volume horaire candidat n'est trouvé
     */
    private function creerContrat($volumesHoraires)
    {
        $this->contrat = $this->getServiceContrat()->newEntity(TypeContrat::CODE_CONTRAT)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setContrat(null) // le contrat initial, c'est lui!
                ->setValidation(null)
                ->setTotalHetd($this->getTotalHetdIntervenant());

        foreach ($volumesHoraires as $volumeHoraire) {
            $this->contrat->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($this->contrat);
            $this->getEntityManager()->persist($volumeHoraire);
        }

        $this->getEntityManager()->persist($this->contrat);
        $this->getEntityManager()->flush();

        return $this->contrat;
    }

    /**
     * Crée un projet d'avenant, c'est à dire un avenant non encore validé.
     *
     * @return Contrat Avenant créé
     */
    private function creerAvenant($volumesHoraires)
    {
        $avenant = $this->getServiceContrat()->newEntity(TypeContrat::CODE_AVENANT)
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure())
                ->setContrat($this->getContratInitial()) // lien vers le contrat initial
                ->setNumeroAvenant($this->getServiceContrat()->getNextNumeroAvenant($this->getIntervenant(), false))
                ->setValidation(null)
                ->setTotalHetd($this->getTotalHetdIntervenant());

        foreach ($volumesHoraires as $volumeHoraire) { /* @var $volumeHoraire \Application\Entity\Db\VolumeHoraire */
            $avenant->addVolumeHoraire($volumeHoraire);
            $volumeHoraire->setContrat($avenant);
//            $this->getEntityManager()->persist($volumeHoraire);
        }

        $this->getEntityManager()->persist($avenant);
        $this->getEntityManager()->flush();

        return $avenant;
    }

    /**
     * Détermine si le projet de contrat spécifié nécessite d'être requalifé en avenant.
     * C'est le cas lorsqu'il existe un projet de contrat validé.
     *
     * @param \Application\Entity\Db\Contrat $contrat
     * @return boolean
     */
    public function getDeviendraAvenant(Contrat $contrat)
    {
        if ($contrat->estUnAvenant() || $contrat->getValidation()) {
            return false;
        }
        if (!$this->getContratValide()) {
            return false;
        }

        return true;
    }

    private $contratValide;

    /**
     * Recherche s'il existe un contrat validé concernant l'intervenant dans
     * n'importe quelle composante.
     *
     * @return Contrat|null
     */
    public function getContratValide()
    {
        if (null === $this->contratValide) {
            $serviceContrat = $this->getServiceContrat();
            $qb = $serviceContrat->finderByType($this->getTypeContrat());
            $qb = $serviceContrat->finderByIntervenant($this->getIntervenant(), $qb);
            $qb = $serviceContrat->finderByTypeValidation(TypeValidation::CODE_CONTRAT, $qb);
            // NB: pas de filtre sur la structure

            $this->contratValide = $qb->getQuery()->getOneOrNullResult();
        }

        return $this->contratValide;
    }

    /**
     * @var array
     */
    private $messages = [];

    /**
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     *
     * @return boolean
     */
    public function getPeutCreerContratInitial()
    {
        $peut = $this->getPeutCreerContratInitialRule()->execute();

        if (!$peut) {
            $this->validationContratInitial = $this->getPeutCreerContratInitialRule()->getValidation();
        }

        return $peut;
    }

    /**
     *
     * @return boolean
     */
    public function getPeutCreerAvenant()
    {
        return $this->getPeutCreerAvenantRule()->execute();
    }

    private $validationContratInitial;

    /**
     *
     * @return \DateTime
     */
    public function getValidationContratInitial()
    {
        return $this->validationContratInitial;
    }

    /**
     *
     * @return PeutCreerContratInitialRule
     */
    private function getPeutCreerContratInitialRule()
    {
        $peutCreerContratRule = $this->getServiceLocator()->get('PeutCreerContratInitialRule');
        $peutCreerContratRule
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());

        return $peutCreerContratRule;
    }

    /**
     *
     * @return PeutCreerAvenantRule
     */
    private function getPeutCreerAvenantRule()
    {
        $peutCreerAvenantRule = $this->getServiceLocator()->get('PeutCreerAvenantRule');
        $peutCreerAvenantRule
                ->setIntervenant($this->getIntervenant())
                ->setStructure($this->getStructure());

        return $peutCreerAvenantRule;
    }

    public function getServicesDisposPourContrat()
    {
        $vhDispos = $this->getVolumesHorairesDisposPourContrat();
        $vhIds    = array_map(function($v) { return $v->getId(); }, $vhDispos);
        $qb       = $this->getServiceService()->getRepo()->createQueryBuilder("s");
        $qb
                ->select("s, ep, vh, str, i")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", \Doctrine\ORM\Query\Expr\Join::WITH, "tvh = :tvh")
                ->join("s.elementPedagogique", "ep")
                ->join("ep.structure", "str")
                ->join("s.intervenant", "i")
                ->andWhere($qb->expr()->in("vh", $vhIds))
                ->setParameter('tvh', $this->getServiceTypeVolumeHoraire()->getPrevu());
        $servicesDisposPourContrat = $qb->getQuery()->getResult();

        return $servicesDisposPourContrat;
    }

    public function getVolumesHorairesDisposPourContrat()
    {
        $volumesHorairesDisposPourContrat = $this->getPeutCreerContratInitialRule()->getVolumesHorairesDispos();

        return $volumesHorairesDisposPourContrat;
    }

    /**
     * Fetche les services auxquels appartiennent les volumes horaires candidats à un avenant.
     *
     * NB: une requête avec jointure entre Service et VolumeHoraire est INDISPENSABLE.
     * Parcourir les volumes horaires pour collecter les services paraît une idée mais
     * NE CONVIENT PAS car les services seraient hydratés avec tous les volumes horaires existants
     * et non ceux réellement disponibles pour un contrat/avenant.
     *
     * @return array
     */
    public function getServicesDisposPourAvenant()
    {
        $vhDispos = $this->getVolumesHorairesDisposPourAvenant();
        $vhIds    = array_map(function($v) { return $v->getId(); }, $vhDispos);
        $qb       = $this->getServiceService()->getRepo()->createQueryBuilder("s");
        $qb
                ->select("s, ep, vh, str, i")
                ->join("s.volumeHoraire", "vh")
                ->join("vh.typeVolumeHoraire", "tvh", \Doctrine\ORM\Query\Expr\Join::WITH, "tvh = :tvh")
                ->join("s.elementPedagogique", "ep")
                ->join("ep.structure", "str")
                ->join("s.intervenant", "i")
                ->andWhere($qb->expr()->in("vh", $vhIds))
                ->setParameter('tvh', $this->getServiceTypeVolumeHoraire()->getPrevu());
        $servicesDisposPourAvenant = $qb->getQuery()->getResult();

        return $servicesDisposPourAvenant;
    }

    public function getVolumesHorairesDisposPourAvenant()
    {
        $volumesHorairesDisposPourAvenant = $this->getPeutCreerAvenantRule()->getVolumesHorairesDispos();

        return $volumesHorairesDisposPourAvenant;
    }

    private function getTypeContrat()
    {
        return $this->getEntityManager()->getRepository('Application\Entity\Db\TypeContrat')
                ->findOneByCode(TypeContrat::CODE_CONTRAT);
    }

    private $contrat;

    /**
     * Recherche le contrat initial de l'intervenant.
     * NB: le contrat initial n'est pas forcément rattaché à la structure courante.
     *
     * @return Contrat
     */
    private function getContratInitial()
    {
        if (null === $this->contrat) {
            $serviceContrat = $this->getServiceContrat();
            $qb = $serviceContrat->finderByType($this->getTypeContrat());
            $serviceContrat->finderByIntervenant($this->getIntervenant(), $qb);
            $this->contrat = $qb->getQuery()->getOneOrNullResult();
        }

        return $this->contrat;
    }

    private $structure;

    private function getStructure()
    {
        if (null === $this->structure) {
            $role = $this->getServiceContext()->getSelectedIdentityRole();
            $this->structure = $role->getStructure();
        }

        return $this->structure;
    }

    /**
     * @return float
     */
    private function getTotalHetdIntervenant()
    {   
        $typeVolumeHoraire = $this->getServiceTypeVolumeHoraire()->getPrevu();
        $etatVolumeHoraire = $this->getServiceEtatVolumeHoraire()->getValide();
        
        $fr = $this->getIntervenant()->getUniqueFormuleResultat($typeVolumeHoraire, $etatVolumeHoraire);

        return $fr->getServiceDu() + $fr->getSolde();
    }
}
