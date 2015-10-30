<?php

namespace Application\Rule\Intervenant;

use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeContratAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Règle métier déterminant si un intervenant a fait l'objet d'un contrat/avenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PossedeContratRule extends AbstractIntervenantRule
{
    use TypeContratAwareTrait;
    use StructureAwareTrait;

    const MESSAGE_AUCUN = 'messageAucun';

    /**
     * Message template definitions
     * @var array
     */
    protected $messageTemplates = [
        self::MESSAGE_AUCUN => "L'intervenant n'a pas de contrat/avenant.",
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
                $this->message(self::MESSAGE_AUCUN);
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
        $qb = $this->getServiceIntervenant()->getEntityManager()->createQueryBuilder()
                ->from("Application\Entity\Db\Intervenant", "i")
                ->select("i.id")
                ->join("i.contrat", "c");

        if ($this->getTypeContrat()) {
            $qb->andWhere("c.typeContrat = " . $this->getTypeContrat()->getId());
        }

        if ($this->getStructure()) {
            $qb->andWhere("c.structure = " . $this->getStructure()->getId());
        }

        if (null !== $this->getValide()) {
            $qb->andWhere($this->getValide() ? "c.validation is NOT null" : "c.validation is null");
        }

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
        if ($this->getIntervenant()) {
            return !$this->getIntervenant()->estPermanent();
        }

        return true;
    }

    /**
     * Témoin indiquant s'il faut prendre en compte :
     * - tous les contrats/avenants : <code>null</code> ;
     * - que les contrats/avenants non validés : <code>false</code> ;
     * - que les contrats/avenants validés : <code>true</code>.
     *
     * @var null|boolean
     */
    protected $valide = null;

    /**
     * Retourne le témoin concernant la validation des contrats/avenants pris en compte.
     *
     * @return boolean|null
     */
    public function getValide()
    {
        return $this->valide;
    }

    /**
     * Spécifie le témoin indiquant s'il faut prendre en compte :
     * - tous les contrats/avenants : <code>null</code> ;
     * - que les contrats/avenants non validés : <code>false</code> ;
     * - que les contrats/avenants validés : <code>true</code>.
     *
     * @param boolean|null $valide
     * @return self
     */
    public function setValide($valide = true)
    {
        $this->valide = $valide;

        return $this;
    }
}
