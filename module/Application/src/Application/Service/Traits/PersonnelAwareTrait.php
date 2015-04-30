<?php

namespace Application\Service\Traits;

use Application\Service\Personnel;
use Common\Exception\RuntimeException;

trait PersonnelAwareTrait
{
    /**
     * description
     *
     * @var Personnel
     */
    private $servicePersonnel;

    /**
     *
     * @param Personnel $servicePersonnel
     * @return self
     */
    public function setServicePersonnel( Personnel $servicePersonnel )
    {
        $this->servicePersonnel = $servicePersonnel;
        return $this;
    }

    /**
     *
     * @return Personnel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServicePersonnel()
    {
        if (empty($this->servicePersonnel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationPersonnel');
        }else{
            return $this->servicePersonnel;
        }
    }

}