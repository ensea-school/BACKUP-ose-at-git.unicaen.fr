<?php

namespace Application\Form\Paiement\Traits;

use Application\Form\Paiement\MiseEnPaiementRechercheForm;
use Application\Module;
use RuntimeException;

/**
 * Description of MiseEnPaiementRechercheFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MiseEnPaiementRechercheFormAwareTrait
{
    /**
     * @var MiseEnPaiementRechercheForm
     */
    private $formPaiementMiseEnPaiementRecherche;





    /**
     * @param MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche
     * @return self
     */
    public function setFormPaiementMiseEnPaiementRecherche( MiseEnPaiementRechercheForm $formPaiementMiseEnPaiementRecherche )
    {
        $this->formPaiementMiseEnPaiementRecherche = $formPaiementMiseEnPaiementRecherche;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MiseEnPaiementRechercheForm
     * @throws RuntimeException
     */
    public function getFormPaiementMiseEnPaiementRecherche()
    {
        if (!empty($this->formPaiementMiseEnPaiementRecherche)){
            return $this->formPaiementMiseEnPaiementRecherche;
        }

        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        return $serviceLocator->get('FormElementManager')->get('PaiementMiseEnPaiementRechercheForm');
    }
}