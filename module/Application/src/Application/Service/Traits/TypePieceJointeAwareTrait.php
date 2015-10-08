<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointe;
use Application\Module;
use RuntimeException;

/**
 * Description of TypePieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeAwareTrait
{
    /**
     * @var TypePieceJointe
     */
    private $serviceTypePieceJointe;





    /**
     * @param TypePieceJointe $serviceTypePieceJointe
     * @return self
     */
    public function setServiceTypePieceJointe( TypePieceJointe $serviceTypePieceJointe )
    {
        $this->serviceTypePieceJointe = $serviceTypePieceJointe;
        return $this;
    }



    /**
     * @return TypePieceJointe
     * @throws RuntimeException
     */
    public function getServiceTypePieceJointe()
    {
        if (empty($this->serviceTypePieceJointe)){
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
        $this->serviceTypePieceJointe = $serviceLocator->get('ApplicationTypePieceJointe');
        }
        return $this->serviceTypePieceJointe;
    }
}