<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceModifie;

/**
 * Description of FormuleServiceModifieAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceModifieAwareTrait
{
    /**
     * @var FormuleServiceModifie
     */
    private $serviceFormuleServiceModifie;



    /**
     * @param FormuleServiceModifie $serviceFormuleServiceModifie
     *
     * @return self
     */
    public function setServiceFormuleServiceModifie(FormuleServiceModifie $serviceFormuleServiceModifie)
    {
        $this->serviceFormuleServiceModifie = $serviceFormuleServiceModifie;

        return $this;
    }



    /**
     * @return FormuleServiceModifie
     */
    public function getServiceFormuleServiceModifie()
    {
        if (empty($this->serviceFormuleServiceModifie)) {
            $this->serviceFormuleServiceModifie = \Application::$container->get('ApplicationFormuleServiceModifie');
        }

        return $this->serviceFormuleServiceModifie;
    }
}