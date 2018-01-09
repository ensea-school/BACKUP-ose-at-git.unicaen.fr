<?php

namespace Application\Service\Traits;

use Application\Service\TblPieceJointeService;

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
     *
     * @return self
     */
    public function setServiceTblPieceJointe(TblPieceJointeService $serviceTblPieceJointe)
    {
        $this->serviceTblPieceJointe = $serviceTblPieceJointe;

        return $this;
    }



    /**
     * @return TblPieceJointeService
     */
    public function getServiceTblPieceJointe()
    {
        if (empty($this->serviceTblPieceJointe)) {
            $this->serviceTblPieceJointe = \Application::$container->get(TblPieceJointeService::class);
        }

        return $this->serviceTblPieceJointe;
    }
}