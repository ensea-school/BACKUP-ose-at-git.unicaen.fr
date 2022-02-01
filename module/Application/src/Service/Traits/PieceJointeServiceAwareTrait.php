<?php

namespace Application\Service\Traits;

use Application\Service\PieceJointeService;

/**
 * Description of PieceJointeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeServiceAwareTrait
{
    protected ?PieceJointeService $servicePieceJointe;



    /**
     * @param PieceJointeService|null $servicePieceJointe
     *
     * @return self
     */
    public function setServicePieceJointe( ?PieceJointeService $servicePieceJointe )
    {
        $this->servicePieceJointe = $servicePieceJointe;

        return $this;
    }



    public function getServicePieceJointe(): ?PieceJointeService
    {
        if (!$this->servicePieceJointe){
            $this->servicePieceJointe = \Application::$container->get(PieceJointeService::class);
        }

        return $this->servicePieceJointe;
    }
}