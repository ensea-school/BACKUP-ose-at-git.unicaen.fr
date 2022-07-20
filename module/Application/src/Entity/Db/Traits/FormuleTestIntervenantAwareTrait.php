<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleTestIntervenant;

/**
 * Description of FormuleTestIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleTestIntervenantAwareTrait
{
    protected ?FormuleTestIntervenant $intervenantTest = null;



    /**
     * @param FormuleTestIntervenant $intervenantTest
     *
     * @return self
     */
    public function setFormuleTestIntervenant(FormuleTestIntervenant $intervenantTest)
    {
        $this->intervenantTest = $intervenantTest;

        return $this;
    }



    public function getFormuleTestIntervenant(): ?FormuleTestIntervenant
    {
        return $this->intervenantTest;
    }
}