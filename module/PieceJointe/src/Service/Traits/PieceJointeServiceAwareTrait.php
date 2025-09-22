<?php

namespace PieceJointe\Service\Traits;

use PieceJointe\Service\PieceJointeService;

/**
 * Description of PieceJointeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeServiceAwareTrait
{
    protected ?PieceJointeService $servicePieceJointe = null;



    /**
     * @param PieceJointeService $servicePieceJointe
     *
     * @return self
     */
    public function setServicePieceJointe(?PieceJointeService $servicePieceJointe)
    {
        $this->servicePieceJointe = $servicePieceJointe;

        return $this;
    }



    public function getServicePieceJointe(): ?PieceJointeService
    {
        if (empty($this->servicePieceJointe)) {
            $this->servicePieceJointe = \Framework\Application\Application::getInstance()->container()->get(PieceJointeService::class);
        }

        return $this->servicePieceJointe;
    }
}