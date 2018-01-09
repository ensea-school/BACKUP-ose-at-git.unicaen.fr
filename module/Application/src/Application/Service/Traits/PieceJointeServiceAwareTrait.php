<?php

namespace Application\Service\Traits;

use Application\Service\PieceJointeService;

/**
 * Description of PieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeServiceAwareTrait
{
    /**
     * @var PieceJointeService
     */
    private $servicePieceJointe;



    /**
     * @param PieceJointeService $servicePieceJointe
     *
     * @return self
     */
    public function setServicePieceJointe(PieceJointeService $servicePieceJointe)
    {
        $this->servicePieceJointe = $servicePieceJointe;

        return $this;
    }



    /**
     * @return PieceJointeService
     */
    public function getServicePieceJointe()
    {
        if (empty($this->servicePieceJointe)) {
            $this->servicePieceJointe = \Application::$container->get(PieceJointeService::class);
        }

        return $this->servicePieceJointe;
    }
}