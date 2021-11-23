<?php

namespace Application\Form\FonctionReferentiel\Traits;

use Application\Form\FonctionReferentiel\FonctionReferentielSaisieForm;

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
     *
     * @return self
     */
    public function setFormFonctionReferentielSaisie(FonctionReferentielSaisieForm $formFonctionReferentielSaisie)
    {
        $this->formFonctionReferentielSaisie = $formFonctionReferentielSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return FonctionReferentielSaisieForm
     */
    public function getFormFonctionReferentielSaisie()
    {
        if (!empty($this->formFonctionReferentielSaisie)) {
            return $this->formFonctionReferentielSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(FonctionReferentielSaisieForm::class);
    }
}

