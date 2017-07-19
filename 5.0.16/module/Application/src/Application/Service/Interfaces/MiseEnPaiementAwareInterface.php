<?php

namespace Application\Service\Interfaces;

use Application\Service\MiseEnPaiement;
use RuntimeException;

/**
 * Description of MiseEnPaiementAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementAwareInterface
{
    /**
     * @param MiseEnPaiement $serviceMiseEnPaiement
     * @return self
     */
    public function setServiceMiseEnPaiement( MiseEnPaiement $serviceMiseEnPaiement );



    /**
     * @return MiseEnPaiementAwareInterface
     * @throws RuntimeException
     */
    public function getServiceMiseEnPaiement();
}