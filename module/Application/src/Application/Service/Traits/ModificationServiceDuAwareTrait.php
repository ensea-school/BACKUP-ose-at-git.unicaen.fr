<?php

namespace Application\Service\Traits;

use Application\Service\ModificationServiceDu;
use Application\Module;
use RuntimeException;

/**
 * Description of ModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuAwareTrait
{
    /**
     * @var ModificationServiceDu
     */
    private $serviceModificationServiceDu;





    /**
     * @param ModificationServiceDu $serviceModificationServiceDu
     * @return self
     */
    public function setServiceModificationServiceDu( ModificationServiceDu $serviceModificationServiceDu )
    {
        $this->serviceModificationServiceDu = $serviceModificationServiceDu;
        return $this;
    }



    /**
     * @return ModificationServiceDu
     * @throws RuntimeException
     */
    public function getServiceModificationServiceDu()
    {
        if (empty($this->serviceModificationServiceDu)){
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
        $this->serviceModificationServiceDu = $serviceLocator->get('ApplicationModificationServiceDu');
        }
        return $this->serviceModificationServiceDu;
    }
}