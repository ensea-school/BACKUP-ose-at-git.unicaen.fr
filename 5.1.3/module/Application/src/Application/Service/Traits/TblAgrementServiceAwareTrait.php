<?php

namespace Application\Service\Traits;

use Application\Service\TblAgrementService;
use Application\Module;
use RuntimeException;

/**
 * Description of TblAgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblAgrementServiceAwareTrait
{
    /**
     * @var TblAgrementService
     */
    private $serviceTblAgrement;





    /**
     * @param TblAgrementService $serviceTblAgrement
     * @return self
     */
    public function setServiceTblAgrement( TblAgrementService $serviceTblAgrement )
    {
        $this->serviceTblAgrement = $serviceTblAgrement;
        return $this;
    }



    /**
     * @return TblAgrementService
     * @throws RuntimeException
     */
    public function getServiceTblAgrement()
    {
        if (empty($this->serviceTblAgrement)){
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
        $this->serviceTblAgrement = $serviceLocator->get('ApplicationTblAgrement');
        }
        return $this->serviceTblAgrement;
    }
}