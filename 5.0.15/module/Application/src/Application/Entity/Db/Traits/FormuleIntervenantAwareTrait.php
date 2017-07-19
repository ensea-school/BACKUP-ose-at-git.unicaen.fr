<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleIntervenant;

/**
 * Description of FormuleIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleIntervenantAwareTrait
{
    /**
     * @var FormuleIntervenant
     */
    private $formuleIntervenant;





    /**
     * @param FormuleIntervenant $formuleIntervenant
     * @return self
     */
    public function setFormuleIntervenant( FormuleIntervenant $formuleIntervenant = null )
    {
        $this->formuleIntervenant = $formuleIntervenant;
        return $this;
    }



    /**
     * @return FormuleIntervenant
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }
}