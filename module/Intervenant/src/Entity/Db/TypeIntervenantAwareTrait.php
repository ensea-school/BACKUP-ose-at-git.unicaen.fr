<?php

namespace Intervenant\Entity\Db;


/**
 * Description of TypeIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeIntervenantAwareTrait
{
    protected ?TypeIntervenant $typeIntervenant = null;



    /**
     * @param TypeIntervenant $typeIntervenant
     *
     * @return self
     */
    public function setTypeIntervenant(?TypeIntervenant $typeIntervenant)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }



    public function getTypeIntervenant(): ?TypeIntervenant
    {
        return $this->typeIntervenant;
    }
}