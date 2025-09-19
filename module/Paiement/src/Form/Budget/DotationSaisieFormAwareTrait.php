<?php

namespace Paiement\Form\Budget;


/**
 * Description of DotationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DotationSaisieFormAwareTrait
{
    protected ?DotationSaisieForm $formBudgetDotationSaisie = null;



    /**
     * @param DotationSaisieForm $formBudgetDotationSaisie
     *
     * @return self
     */
    public function setFormBudgetDotationSaisie(?DotationSaisieForm $formBudgetDotationSaisie)
    {
        $this->formBudgetDotationSaisie = $formBudgetDotationSaisie;

        return $this;
    }



    public function getFormBudgetDotationSaisie(): ?DotationSaisieForm
    {
        if (!empty($this->formBudgetDotationSaisie)) {
            return $this->formBudgetDotationSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DotationSaisieForm::class);
    }
}