<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\TypeValidation;
use Application\Service\TypeValidation as TypeValidationService;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;

/**
 * Recherche les intervenants dont le référentiel a été validé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ReferentielValideRule extends AbstractIntervenantRule
{
    const MESSAGE_AUCUNE = 'messageAucune';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_AUCUNE => "Le référentiel de %value% n'a fait l'objet d'aucune validation.",
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
            $result = $qb->getQuery()->getScalarResult();

            if (!$result) {
                $this->message(self::MESSAGE_AUCUNE);
            }

            return $this->normalizeResult($result);
        }

        /**
         * Recherche des intervenants répondant à la règle
         */

        $result = $qb->getQuery()->getScalarResult();

        return $this->normalizeResult($result);
    }

    public function isRelevant()
    {
        if ($this->getIntervenant()) {
            return $this->getIntervenant()->getStatut()->getPeutSaisirReferentiel();
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
        $qb = $em->getRepository('Application\Entity\Db\IntervenantPermanent')->createQueryBuilder("i")
                ->select("i.id")
                ->distinct()
                ->join("i.serviceReferentiel", 'r')
                ->join("r.fonction", 'f')    // nécessaire pour écarter le référentiel si la fonction est historisée
                ->join("i.validation", "v", Join::WITH, "v.typeValidation = " . $this->getTypeValidationReferentiel()->getId())
                ->join("v.typeValidation", "tv");

        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }

        return $qb;
    }

    /**
     * @return TypeValidation
     */
    private function getTypeValidationReferentiel()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_REFERENTIEL);
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