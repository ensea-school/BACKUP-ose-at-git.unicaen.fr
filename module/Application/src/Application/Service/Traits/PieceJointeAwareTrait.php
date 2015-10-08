<?php

namespace Application\Service\Traits;

use Application\Service\PieceJointe;
use Application\Module;
use RuntimeException;

/**
 * Description of PieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeAwareTrait
{
    /**
     * @var PieceJointe
     */
    private $servicePieceJointe;





    /**
     * @param PieceJointe $servicePieceJointe
     * @return self
     */
    public function setServicePieceJointe( PieceJointe $servicePieceJointe )
    {
        $this->servicePieceJointe = $servicePieceJointe;
        return $this;
    }



    /**
     * @return PieceJointe
     * @throws RuntimeException
     */
    public function getServicePieceJointe()
    {
        if (empty($this->servicePieceJointe)){
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
        $this->servicePieceJointe = $serviceLocator->get('ApplicationPieceJointe');
        }
        return $this->servicePieceJointe;
    }
}