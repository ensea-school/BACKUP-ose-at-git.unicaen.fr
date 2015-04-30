<?php

namespace Application\Service\Traits;

use Application\Service\Perimetre;
use Common\Exception\RuntimeException;

trait PerimetreAwareTrait
{
    /**
     * description
     *
     * @var Perimetre
     */
    private $servicePerimetre;

    /**
     *
     * @param Perimetre $servicePerimetre
     * @return self
     */
    public function setServicePerimetre( Perimetre $servicePerimetre )
    {
        $this->servicePerimetre = $servicePerimetre;
        return $this;
    }

    /**
     *
     * @return Perimetre
     * @throws \Common\Exception\RuntimeException
     */
    public function getServicePerimetre()
    {
        if (empty($this->servicePerimetre)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationPerimetre');
        }else{
            return $this->servicePerimetre;
        }
    }

}