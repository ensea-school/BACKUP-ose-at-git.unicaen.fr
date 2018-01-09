<?php

namespace Application\Form\Budget\Traits;

use Application\Form\Budget\DotationSaisieForm;

/**
 * Description of DotationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationSaisieFormAwareTrait
{
    /**
     * @var DotationSaisieForm
     */
    private $formBudgetDotationSaisie;



    /**
     * @param DotationSaisieForm $formBudgetDotationSaisie
     *
     * @return self
     */
    public function setFormBudgetDotationSaisie(DotationSaisieForm $formBudgetDotationSaisie)
    {
        $this->formBudgetDotationSaisie = $formBudgetDotationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DotationSaisieForm
     */
    public function getFormBudgetDotationSaisie()
    {
        if (!empty($this->formBudgetDotationSaisie)) {
            return $this->formBudgetDotationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(DotationSaisieForm::class);
    }
}