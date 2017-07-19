<?php

namespace Application\Form\Paiement\Interfaces;

use Application\Form\Paiement\MiseEnPaiementForm;
use RuntimeException;

/**
 * Description of MiseEnPaiementFormAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementFormAwareInterface
{
    /**
     * @param MiseEnPaiementForm $formPaiementMiseEnPaiement
     * @return self
     */
    public function setFormPaiementMiseEnPaiement( MiseEnPaiementForm $formPaiementMiseEnPaiement );



    /**
     * @return MiseEnPaiementFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormPaiementMiseEnPaiement();
}