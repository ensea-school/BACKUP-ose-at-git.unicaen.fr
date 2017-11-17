<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultat;

/**
 * Description of FormuleResultatAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatAwareTrait
{
    /**
     * @var FormuleResultat
     */
    private $serviceFormuleResultat;



    /**
     * @param FormuleResultat $serviceFormuleResultat
     *
     * @return self
     */
    public function setServiceFormuleResultat(FormuleResultat $serviceFormuleResultat)
    {
        $this->serviceFormuleResultat = $serviceFormuleResultat;

        return $this;
    }



    /**
     * @return FormuleResultat
     */
    public function getServiceFormuleResultat()
    {
        if (empty($this->serviceFormuleResultat)) {
            $this->serviceFormuleResultat = \Application::$container->get('ApplicationFormuleResultat');
        }

        return $this->serviceFormuleResultat;
    }
}