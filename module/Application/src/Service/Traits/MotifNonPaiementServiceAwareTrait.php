<?php

namespace Application\Service\Traits;

use Application\Service\MotifNonPaiementService;

/**
 * Description of MotifNonPaiementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementServiceAwareTrait
{
    protected ?MotifNonPaiementService $serviceMotifNonPaiement = null;



    /**
     * @param MotifNonPaiementService $serviceMotifNonPaiement
     *
     * @return self
     */
    public function setServiceMotifNonPaiement( MotifNonPaiementService $serviceMotifNonPaiement )
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;

        return $this;
    }



    public function getServiceMotifNonPaiement(): ?MotifNonPaiementService
    {
        if (empty($this->serviceMotifNonPaiement)){
            $this->serviceMotifNonPaiement = \Application::$container->get(MotifNonPaiementService::class);
        }

        return $this->serviceMotifNonPaiement;
    }
}