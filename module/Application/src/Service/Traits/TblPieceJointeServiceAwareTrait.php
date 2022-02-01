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
    protected ?TblPieceJointeService $serviceTblPieceJointe;



    /**
     * @param TblPieceJointeService|null $serviceTblPieceJointe
     *
     * @return self
     */
    public function setServiceTblPieceJointe( ?TblPieceJointeService $serviceTblPieceJointe )
    {
        $this->serviceTblPieceJointe = $serviceTblPieceJointe;

        return $this;
    }



    public function getServiceTblPieceJointe(): ?TblPieceJointeService
    {
        if (!$this->serviceTblPieceJointe){
            $this->serviceTblPieceJointe = \Application::$container->get(TblPieceJointeService::class);
        }

        return $this->serviceTblPieceJointe;
    }
}