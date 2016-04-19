<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Structure;
use LogicException;

/**
 * Règle métier déterminant si du référentiel peut être saisi.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PeutSaisirReferentielRule extends AbstractIntervenantRule
{
    use StructureAwareTrait;

    const MESSAGE_STATUT     = 'messageStatut';
    const MESSAGE_STRUCTURE  = 'messageStructure';
    const MESSAGE_IMPOSSIBLE = 'messageStatutOuStructure';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_STATUT     => "Le statut &laquo; %value% &raquo; n'autorise pas la saisie de référentiel.",
        self::MESSAGE_STRUCTURE  => "La saisie de référentiel au sein de la structure &laquo; %value% &raquo; n'est pas possible.",
        self::MESSAGE_IMPOSSIBLE => "La saisie de référentiel n'est pas possible.",
    ];

    /**
     *
     * @return array
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
                $statut = $this->getIntervenant()->getStatut();
                $this->message(self::MESSAGE_IMPOSSIBLE, $statut);
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
        return true;
    }

    /**
     *
     * @return QueryBuilder
     */
    public function getQueryBuilder()
    {
        $qb = $this->getServiceIntervenant()->getRepo()->createQueryBuilder("i")
                ->select("i.id")
                ->join("i.statut", "s")
                ->andWhere("s.peutSaisirReferentiel = 1");

        if ($this->getStructure()) {
            $qb
                    ->join("i.structure", "saff")
                    ->join("saff", "saff2")
                    ->andWhere("saff2 = " . $this->getStructure()->getId());
        }

        if ($this->getIntervenant()) {
            $qb->andWhere("i = " . $this->getIntervenant()->getId());
        }

        return $qb;
    }

    /**
     * Spécifie la structure concernée.
     *
     * @param Structure $structure Structure concernée
     */
    public function setStructure(Structure $structure = null)
    {
        if ($structure && 2 !== $structure->getNiveau()) {
            throw new LogicException("La structure spécifiée doit être de niveau 2.");
        }

        $this->structure = $structure;

        return $this;
    }
}