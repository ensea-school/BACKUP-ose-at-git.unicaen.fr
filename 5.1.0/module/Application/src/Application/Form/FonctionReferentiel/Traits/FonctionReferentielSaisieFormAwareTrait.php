<?php

namespace Application\Form\FonctionReferentiel\Traits;

use Application\Form\FonctionReferentiel\FonctionReferentielSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of FonctionReferentielSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielSaisieFormAwareTrait
{
    /**
     * @var FonctionReferentielSaisieForm
     */
    private $formFonctionReferentielSaisie;


    /**
     * @param FonctionReferentielSaisieForm $formFonctionReferentielSaisie
     * @return self
     */
    public function setFormFonctionReferentielSaisie( FonctionReferentielSaisieForm $formFonctionReferentielSaisie )
    {
        $this->formFonctionReferentielSaisie = $formFonctionReferentielSaisie;
        return $this;
    }


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return FonctionReferentielSaisieForm
     * @throws RuntimeException
     */
    public function getFormFonctionReferentielSaisie()
    {
        if (!empty($this->formFonctionReferentielSaisie)){
            return $this->formFonctionReferentielSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('FonctionReferentielSaisie');
    }
}

