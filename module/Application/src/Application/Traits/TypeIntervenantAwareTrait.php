<?php

namespace Application\Traits;

use Application\Entity\Db\TypeIntervenant;

/**
 * Description of TypeIntervenantAwareTrait
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait TypeIntervenantAwareTrait
{
    /**
     * @var TypeIntervenant
     */
    protected $typeIntervenant;

    /**
     * Spécifie le type d'intervenant concerné.
     *
     * @param TypeIntervenant $typeIntervenant le type d'intervenant concerné
     */
    public function setTypeIntervenant(TypeIntervenant $typeIntervenant = null)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }

    /**
     * Retourne le type d'intervenant concerné.
     *
     * @return TypeIntervenant
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }
}