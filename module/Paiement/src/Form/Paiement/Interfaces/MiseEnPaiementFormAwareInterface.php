<?php

namespace Paiement\Form\Paiement\Interfaces;

use Paiement\Form\Paiement\MiseEnPaiementForm;

/**
 * Description of MiseEnPaiementFormAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementFormAwareInterface
{
    /**
     * @param MiseEnPaiementForm|null $formPaiementMiseEnPaiement
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiement( ?MiseEnPaiementForm $formPaiementMiseEnPaiement );



    public function getFormPaiementMiseEnPaiement(): ?MiseEnPaiementForm;
}