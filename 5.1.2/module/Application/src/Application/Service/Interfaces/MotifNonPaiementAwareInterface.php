<?php

namespace Application\Service\Interfaces;

use Application\Service\MotifNonPaiement;
use RuntimeException;

/**
 * Description of MotifNonPaiementAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifNonPaiementAwareInterface
{
    /**
     * @param MotifNonPaiement $serviceMotifNonPaiement
     * @return self
     */
    public function setServiceMotifNonPaiement( MotifNonPaiement $serviceMotifNonPaiement );



    /**
     * @return MotifNonPaiementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceMotifNonPaiement();
}