<?php

namespace Application\Service\Traits;

use Application\Service\Validation;

/**
 * Description of ValidationAwareTrait
 *
 * @author UnicaenCode
 */
trait ValidationAwareTrait
{
    /**
     * @var Validation
     */
    private $serviceValidation;



    /**
     * @param Validation $serviceValidation
     *
     * @return self
     */
    public function setServiceValidation(Validation $serviceValidation)
    {
        $this->serviceValidation = $serviceValidation;

        return $this;
    }



    /**
     * @return Validation
     */
    public function getServiceValidation()
    {
        if (empty($this->serviceValidation)) {
            $this->serviceValidation = \Application::$container->get('ApplicationValidation');
        }

        return $this->serviceValidation;
    }
}