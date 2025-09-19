<?php

namespace Paiement\Service;


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
    public function setServiceMotifNonPaiement(?MotifNonPaiementService $serviceMotifNonPaiement)
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;

        return $this;
    }



    public function getServiceMotifNonPaiement(): ?MotifNonPaiementService
    {
        if (empty($this->serviceMotifNonPaiement)) {
            $this->serviceMotifNonPaiement = \Framework\Application\Application::getInstance()->container()->get(MotifNonPaiementService::class);
        }

        return $this->serviceMotifNonPaiement;
    }
}