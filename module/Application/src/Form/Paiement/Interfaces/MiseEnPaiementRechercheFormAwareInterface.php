<?php

namespace Application\Form\Paiement\Interfaces;

use Application\Form\Paiement\MiseEnPaiementRechercheForm;

/**
 * Description of MiseEnPaiementRechercheFormAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementRechercheFormAwareInterface
{
    /**
     * @param MiseEnPaiementRechercheForm|null $formPaiementMiseEnPaiementRecherche
     *
     * @return self
     */
    public function setFormPaiementMiseEnPaiementRecherche( MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche );



    public function getFormPaiementMiseEnPaiementRecherche(): ?MiseEnPaiementRechercheForm;
}