<?php

namespace Application\Service\Traits;

use Application\Service\TblPieceJointeService;
use Application\Module;
use RuntimeException;

/**
 * Description of TblPieceJointeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeServiceAwareTrait
{
    /**
     * @var TblPieceJointeService
     */
    private $serviceTblPieceJointe;





    /**
     * @param TblPieceJointeService $serviceTblPieceJointe
     * @return self
     */
    public function setServiceTblPieceJointe( TblPieceJointeService $serviceTblPieceJointe )
    {
        $this->serviceTblPieceJointe = $serviceTblPieceJointe;
        return $this;
    }



    /**
     * @return TblPieceJointeService
     * @throws RuntimeException
     */
    public function getServiceTblPieceJointe()
    {
        if (empty($this->serviceTblPieceJointe)){
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
            $this->serviceTblPieceJointe = $serviceLocator->get('applicationTblPieceJointe');
        }
        return $this->serviceTblPieceJointe;
    }
}