<?php

namespace Application\Service\Traits;

use Application\Service\PieceJointe;

/**
 * Description of PieceJointeAwareTrait
 *
 * @author UnicaenCode
 */
trait PieceJointeAwareTrait
{
    /**
     * @var PieceJointe
     */
    private $servicePieceJointe;



    /**
     * @param PieceJointe $servicePieceJointe
     *
     * @return self
     */
    public function setServicePieceJointe(PieceJointe $servicePieceJointe)
    {
        $this->servicePieceJointe = $servicePieceJointe;

        return $this;
    }



    /**
     * @return PieceJointe
     */
    public function getServicePieceJointe()
    {
        if (empty($this->servicePieceJointe)) {
            $this->servicePieceJointe = \Application::$container->get('ApplicationPieceJointe');
        }

        return $this->servicePieceJointe;
    }
}