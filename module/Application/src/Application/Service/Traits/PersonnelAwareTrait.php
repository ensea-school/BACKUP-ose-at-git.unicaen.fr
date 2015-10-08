<?php

namespace Application\Service\Traits;

use Application\Service\Personnel;
use Application\Module;
use RuntimeException;

/**
 * Description of PersonnelAwareTrait
 *
 * @author UnicaenCode
 */
trait PersonnelAwareTrait
{
    /**
     * @var Personnel
     */
    private $servicePersonnel;





    /**
     * @param Personnel $servicePersonnel
     * @return self
     */
    public function setServicePersonnel( Personnel $servicePersonnel )
    {
        $this->servicePersonnel = $servicePersonnel;
        return $this;
    }



    /**
     * @return Personnel
     * @throws RuntimeException
     */
    public function getServicePersonnel()
    {
        if (empty($this->servicePersonnel)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->servicePersonnel = $serviceLocator->get('ApplicationPersonnel');
        }
        return $this->servicePersonnel;
    }
}