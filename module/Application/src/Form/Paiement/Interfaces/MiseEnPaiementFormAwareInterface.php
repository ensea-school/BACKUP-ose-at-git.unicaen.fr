<?php

namespace Application\Form\Paiement\Interfaces;

use Application\Form\Paiement\MiseEnPaiementForm;

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