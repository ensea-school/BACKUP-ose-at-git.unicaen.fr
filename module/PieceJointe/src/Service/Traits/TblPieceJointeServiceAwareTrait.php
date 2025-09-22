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
        if (empty($this->serviceTblPieceJointe)) {
            $this->serviceTblPieceJointe = \Framework\Application\Application::getInstance()->container()->get(TblPieceJointeService::class);
        }

        return $this->serviceTblPieceJointe;
    }
}