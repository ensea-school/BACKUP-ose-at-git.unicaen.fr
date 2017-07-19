<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeStatut;
use Application\Module;
use RuntimeException;

/**
 * Description of TypePieceJointeStatutAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePieceJointeStatutAwareTrait
{
    /**
     * @var TypePieceJointeStatut
     */
    private $serviceTypePieceJointeStatut;





    /**
     * @param TypePieceJointeStatut $serviceTypePieceJointeStatut
     * @return self
     */
    public function setServiceTypePieceJointeStatut( TypePieceJointeStatut $serviceTypePieceJointeStatut )
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;
        return $this;
    }



    /**
     * @return TypePieceJointeStatut
     * @throws RuntimeException
     */
    public function getServiceTypePieceJointeStatut()
    {
        if (empty($this->serviceTypePieceJointeStatut)){
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
        $this->serviceTypePieceJointeStatut = $serviceLocator->get('ApplicationTypePieceJointeStatut');
        }
        return $this->serviceTypePieceJointeStatut;
    }
}