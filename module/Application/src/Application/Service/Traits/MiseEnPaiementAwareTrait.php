<?php

namespace Application\Service\Traits;

use Application\Service\MiseEnPaiement;

/**
 * Description of MiseEnPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementAwareTrait
{
    /**
     * @var MiseEnPaiement
     */
    private $serviceMiseEnPaiement;



    /**
     * @param MiseEnPaiement $serviceMiseEnPaiement
     *
     * @return self
     */
    public function setServiceMiseEnPaiement(MiseEnPaiement $serviceMiseEnPaiement)
    {
        $this->serviceMiseEnPaiement = $serviceMiseEnPaiement;

        return $this;
    }



    /**
     * @return MiseEnPaiement
     */
    public function getServiceMiseEnPaiement()
    {
        if (empty($this->serviceMiseEnPaiement)) {
            $this->serviceMiseEnPaiement = \Application::$container->get('ApplicationMiseEnPaiement');
        }

        return $this->serviceMiseEnPaiement;
    }
}