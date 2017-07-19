<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceDu;
use Application\Module;
use RuntimeException;

/**
 * Description of MotifModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuAwareTrait
{
    /**
     * @var MotifModificationServiceDu
     */
    private $serviceMotifModificationServiceDu;





    /**
     * @param MotifModificationServiceDu $serviceMotifModificationServiceDu
     * @return self
     */
    public function setServiceMotifModificationServiceDu( MotifModificationServiceDu $serviceMotifModificationServiceDu )
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;
        return $this;
    }



    /**
     * @return MotifModificationServiceDu
     * @throws RuntimeException
     */
    public function getServiceMotifModificationServiceDu()
    {
        if (empty($this->serviceMotifModificationServiceDu)){
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
        $this->serviceMotifModificationServiceDu = $serviceLocator->get('ApplicationMotifModificationServiceDu');
        }
        return $this->serviceMotifModificationServiceDu;
    }
}