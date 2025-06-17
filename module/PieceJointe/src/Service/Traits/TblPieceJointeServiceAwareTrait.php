<?php

namespace PieceJointe\Service\Traits;

use PieceJointe\Service\TblPieceJointeService;

/**
 * Description of TblPieceJointeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblPieceJointeServiceAwareTrait
{
    protected ?TblPieceJointeService $serviceTblPieceJointe = null;



    /**
     * @param TblPieceJointeService $serviceTblPieceJointe
     *
     * @return self
     */
    public function setServiceTblPieceJointe(?TblPieceJointeService $serviceTblPieceJointe)
    {
        $this->serviceTblPieceJointe = $serviceTblPieceJointe;

        return $this;
    }



    public function getServiceTblPieceJointe(): ?TblPieceJointeService
    {
        return $this->serviceTblPieceJointe;
    }
}