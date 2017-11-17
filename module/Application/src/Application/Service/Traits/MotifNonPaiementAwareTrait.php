<?php

namespace Application\Service\Traits;

use Application\Service\MotifNonPaiement;

/**
 * Description of MotifNonPaiementAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementAwareTrait
{
    /**
     * @var MotifNonPaiement
     */
    private $serviceMotifNonPaiement;



    /**
     * @param MotifNonPaiement $serviceMotifNonPaiement
     *
     * @return self
     */
    public function setServiceMotifNonPaiement(MotifNonPaiement $serviceMotifNonPaiement)
    {
        $this->serviceMotifNonPaiement = $serviceMotifNonPaiement;

        return $this;
    }



    /**
     * @return MotifNonPaiement
     */
    public function getServiceMotifNonPaiement()
    {
        if (empty($this->serviceMotifNonPaiement)) {
            $this->serviceMotifNonPaiement = \Application::$container->get('ApplicationMotifNonPaiement');
        }

        return $this->serviceMotifNonPaiement;
    }
}