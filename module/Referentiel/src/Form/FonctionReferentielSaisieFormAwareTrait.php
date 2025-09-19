<?php

namespace Referentiel\Form;

/**
 * Description of FonctionReferentielSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait FonctionReferentielSaisieFormAwareTrait
{
    protected ?FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie = null;



    /**
     * @param FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie
     *
     * @return self
     */
    public function setFormFonctionReferentielFonctionReferentielSaisie(?FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie)
    {
        $this->formFonctionReferentielFonctionReferentielSaisie = $formFonctionReferentielFonctionReferentielSaisie;

        return $this;
    }



    public function getFormFonctionReferentielFonctionReferentielSaisie(): ?FonctionReferentielSaisieForm
    {
        if (!empty($this->formFonctionReferentielFonctionReferentielSaisie)) {
            return $this->formFonctionReferentielFonctionReferentielSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(FonctionReferentielSaisieForm::class);
    }
}