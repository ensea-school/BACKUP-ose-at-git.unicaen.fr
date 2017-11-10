<?php

namespace Application\Service\Traits;

use Application\Service\TypePieceJointeStatutService;
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
     * @var TypePieceJointeStatutService
     */
    private $serviceTypePieceJointeStatut;





    /**
     * @param TypePieceJointeStatutService $serviceTypePieceJointeStatut
     *
     * @return self
     */
    public function setServiceTypePieceJointeStatut(TypePieceJointeStatutService $serviceTypePieceJointeStatut )
    {
        $this->serviceTypePieceJointeStatut = $serviceTypePieceJointeStatut;
        return $this;
    }



    /**
     * @return TypePieceJointeStatutService
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
        $this->serviceTypePieceJointeStatut = $serviceLocator->get(TypePieceJointeStatutService::class);
        }
        return $this->serviceTypePieceJointeStatut;
    }
}