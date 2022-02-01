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
    protected ?FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie = null;



    /**
     * @param FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie
     *
     * @return self
     */
    public function setFormFonctionReferentielFonctionReferentielSaisie( ?FonctionReferentielSaisieForm $formFonctionReferentielFonctionReferentielSaisie )
    {
        $this->formFonctionReferentielFonctionReferentielSaisie = $formFonctionReferentielFonctionReferentielSaisie;

        return $this;
    }



    public function getFormFonctionReferentielFonctionReferentielSaisie(): ?FonctionReferentielSaisieForm
    {
        if (empty($this->formFonctionReferentielFonctionReferentielSaisie)){
            $this->formFonctionReferentielFonctionReferentielSaisie = \Application::$container->get('FormElementManager')->get(FonctionReferentielSaisieForm::class);
        }

        return $this->formFonctionReferentielFonctionReferentielSaisie;
    }
}