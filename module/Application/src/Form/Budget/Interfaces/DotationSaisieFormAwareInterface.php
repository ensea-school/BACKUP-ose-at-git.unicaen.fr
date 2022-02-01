<?php

namespace Application\Form\Budget\Interfaces;

use Application\Form\Budget\DotationSaisieForm;

/**
 * Description of DotationSaisieFormAwareInterface
 *
 * @author UnicaenCode
 */
interface DotationSaisieFormAwareInterface
{
    /**
     * @param DotationSaisieForm|null $formBudgetDotationSaisie
     *
     * @return self
     */
    public function setFormBudgetDotationSaisie( DotationSaisieForm $formBudgetDotationSaisie );



    public function getFormBudgetDotationSaisie(): ?DotationSaisieForm;
}