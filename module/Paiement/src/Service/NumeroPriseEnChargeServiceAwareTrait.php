<?php

namespace Paiement\Service;


/**
 * Description of NumeroPriseEnChargeServiceAwareTrait
 *
 * @author LE COURTES Antony <antony.lecourtes at unicaen.fr>
 */
trait NumeroPriseEnChargeServiceAwareTrait
{
    protected ?NumeroPriseEnChargeService $serviceNumeroPriseEnCharge = null;



    /**
     * @param NumeroPriseEnChargeService $serviceNumeroPriseEnCharge
     *
     * @return self
     */
    public function setServiceNumeroPriseEnCharge (?NumeroPriseEnChargeService $serviceNumeroPriseEnCharge)
    {
        $this->serviceNumeroPriseEnCharge = $serviceNumeroPriseEnCharge;

        return $this;
    }



    public function getServiceNumeroPriseEnCharge (): ?NumeroPriseEnChargeService
    {
        if (empty($this->serviceNumeroPriseEnCharge)) {
            $this->serviceNumeroPriseEnCharge = \OseAdmin::instance()->container()->get(NumeroPriseEnChargeService::class);
        }

        return $this->serviceNumeroPriseEnCharge;
    }
}