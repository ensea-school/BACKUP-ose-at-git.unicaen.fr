<?php

namespace Application\Service\Traits;

use Application\Service\MotifNonPaiementService;

/**
 * Description of MotifNonPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementServiceAwareTrait
{
    /**
     * @var MotifNonPaiementService
     */
    private $serviceMotifNonPaiement;



    /**
     * @param MotifNonPaiementService $serviceMotifNonPaiement
     *
     * @return self
     */
    public function setServiceMotifNonPaiement(MotifNonPaiementService $serviceMotifNonPaiement)
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;

        return $this;
    }



    /**
     * @return MotifNonPaiementService
     */
    public function getServiceMotifNonPaiement()
    {
        if (empty($this->serviceMotifNonPaiement)) {
            $this->serviceMotifNonPaiement = \Application::$container->get(MotifNonPaiementService::class);
        }

        return $this->serviceMotifNonPaiement;
    }
}