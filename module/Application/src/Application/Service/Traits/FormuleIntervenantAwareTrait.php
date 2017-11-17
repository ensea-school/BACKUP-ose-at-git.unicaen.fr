<?php

namespace Application\Service\Traits;

use Application\Service\FormuleIntervenant;

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
    private $serviceFormuleIntervenant;



    /**
     * @param FormuleIntervenant $serviceFormuleIntervenant
     *
     * @return self
     */
    public function setServiceFormuleIntervenant(FormuleIntervenant $serviceFormuleIntervenant)
    {
        $this->serviceFormuleIntervenant = $serviceFormuleIntervenant;

        return $this;
    }



    /**
     * @return FormuleIntervenant
     */
    public function getServiceFormuleIntervenant()
    {
        if (empty($this->serviceFormuleIntervenant)) {
            $this->serviceFormuleIntervenant = \Application::$container->get('ApplicationFormuleIntervenant');
        }

        return $this->serviceFormuleIntervenant;
    }
}