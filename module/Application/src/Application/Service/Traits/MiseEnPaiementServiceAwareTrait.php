<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiementService;

/**
 * Description of MiseEnPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementServiceAwareTrait
{
    /**
     * @var MiseEnPaiementService
     */
    private $serviceMiseEnPaiement;



    /**
     * @param MiseEnPaiementService $serviceMiseEnPaiement
     *
     * @return self
     */
    public function setServiceMiseEnPaiement(MiseEnPaiementService $serviceMiseEnPaiement)
    {
        $this->serviceMiseEnPaiement = $serviceMiseEnPaiement;

        return $this;
    }



    /**
     * @return MiseEnPaiementService
     */
    public function getServiceMiseEnPaiement()
    {
        if (empty($this->serviceMiseEnPaiement)) {
            $this->serviceMiseEnPaiement = \Application::$container->get(MiseEnPaiementService::class);
        }

        return $this->serviceMiseEnPaiement;
    }
}