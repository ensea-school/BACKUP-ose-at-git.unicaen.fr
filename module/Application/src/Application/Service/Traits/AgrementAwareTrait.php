<?php

namespace Application\Service\Traits;

use Application\Service\Agrement;

/**
 * Description of AgrementAwareTrait
 *
 * @author UnicaenCode
 */
trait AgrementAwareTrait
{
    /**
     * @var Agrement
     */
    private $serviceAgrement;



    /**
     * @param Agrement $serviceAgrement
     *
     * @return self
     */
    public function setServiceAgrement(Agrement $serviceAgrement)
    {
        $this->serviceAgrement = $serviceAgrement;

        return $this;
    }



    /**
     * @return Agrement
     */
    public function getServiceAgrement()
    {
        if (empty($this->serviceAgrement)) {
            $this->serviceAgrement = \Application::$container->get('ApplicationAgrement');
        }

        return $this->serviceAgrement;
    }
}