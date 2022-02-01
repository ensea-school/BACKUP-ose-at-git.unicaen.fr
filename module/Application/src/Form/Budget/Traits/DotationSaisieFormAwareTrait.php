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
    protected ?DotationSaisieForm $formBudgetDotationSaisie = null;



    /**
     * @param DotationSaisieForm $formBudgetDotationSaisie
     *
     * @return self
     */
    public function setFormBudgetDotationSaisie( DotationSaisieForm $formBudgetDotationSaisie )
    {
        $this->formBudgetDotationSaisie = $formBudgetDotationSaisie;

        return $this;
    }



    public function getFormBudgetDotationSaisie(): ?DotationSaisieForm
    {
        if (empty($this->formBudgetDotationSaisie)){
            $this->formBudgetDotationSaisie = \Application::$container->get('FormElementManager')->get(DotationSaisieForm::class);
        }

        return $this->formBudgetDotationSaisie;
    }
}