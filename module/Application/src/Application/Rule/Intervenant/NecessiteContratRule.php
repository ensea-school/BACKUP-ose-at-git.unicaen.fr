<?php

namespace Application\Rule\Intervenant;

use Doctrine\ORM\QueryBuilder;

/**
 * Règle métier déterminant les intervenants nécessitant l'établissement d'un contrat/avenant.
 *
 * Si un intervenant précis est spécifié, l'exécution de la règle ne porte que sur cet intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class NecessiteContratRule extends AbstractIntervenantRule
{
    const MESSAGE_AUCUN = 'messageAucun';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_AUCUN => "Le statut &laquo; %value% &raquo; ne nécessite pas l'établissement d'un contrat.",
    ];

    /**
     * Exécute la règle métier.
     *
     * @return array [ {id} => [ 'id' => {id} ] ]
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
                $this->message(self::MESSAGE_AUCUN, $this->getIntervenant()->getStatut());
            }

            return $this->normalizeResult($result);
        }

        /**
         * Recherche des intervenants répondant à la règle
         */

        $result = $qb->getQuery()->getScalarResult();

        return $this->normalizeResult($result);
    }

    /**
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.statut", "si")
                ->andWhere("si.peutAvoirContrat = 1");

        /**
         * Application de la règle à un intervenant précis
         */
        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }

        return $qb;
    }

    public function isRelevant()
    {
        return true;
    }
}