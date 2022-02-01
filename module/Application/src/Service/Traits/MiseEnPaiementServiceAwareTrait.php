<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementService;

/**
 * Description of MiseEnPaiementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementServiceAwareTrait
{
    protected ?MiseEnPaiementService $serviceMiseEnPaiement;



    /**
     * @param MiseEnPaiementService|null $serviceMiseEnPaiement
     *
     * @return self
     */
    public function setServiceMiseEnPaiement( ?MiseEnPaiementService $serviceMiseEnPaiement )
    {
        $this->serviceMiseEnPaiement = $serviceMiseEnPaiement;

        return $this;
    }



    public function getServiceMiseEnPaiement(): ?MiseEnPaiementService
    {
        if (!$this->serviceMiseEnPaiement){
            $this->serviceMiseEnPaiement = \Application::$container->get(MiseEnPaiementService::class);
        }

        return $this->serviceMiseEnPaiement;
    }
}