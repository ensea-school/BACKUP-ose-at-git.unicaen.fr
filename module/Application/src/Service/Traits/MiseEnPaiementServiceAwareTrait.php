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
    protected ?MiseEnPaiementService $serviceMiseEnPaiement = null;



    /**
     * @param MiseEnPaiementService $serviceMiseEnPaiement
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
        if (empty($this->serviceMiseEnPaiement)){
            $this->serviceMiseEnPaiement = \Application::$container->get(MiseEnPaiementService::class);
        }

        return $this->serviceMiseEnPaiement;
    }
}