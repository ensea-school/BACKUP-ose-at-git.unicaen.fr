<?php

namespace Application\Form\Paiement\Interfaces;

use Application\Form\Paiement\MiseEnPaiementRechercheForm;
use RuntimeException;

/**
 * Description of MiseEnPaiementRechercheFormAwareInterface
 *
 * @author UnicaenCode
 */
interface MiseEnPaiementRechercheFormAwareInterface
{
    /**
     * @param MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche
     * @return self
     */
    public function setFormPaiementMiseEnPaiementRecherche( MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche );



    /**
     * @return MiseEnPaiementRechercheFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormPaiementMiseEnPaiementRecherche();
}