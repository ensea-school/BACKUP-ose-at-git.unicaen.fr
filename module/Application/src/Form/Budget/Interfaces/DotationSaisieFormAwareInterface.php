<?php

namespace Application\Form\Budget\Interfaces;

use Application\Form\Budget\DotationSaisieForm;
use RuntimeException;

/**
 * Description of DotationSaisieFormAwareInterface
 *
 * @author UnicaenCode
 */
interface DotationSaisieFormAwareInterface
{
    /**
     * @param DotationSaisieForm $formBudgetDotationSaisie
     * @return self
     */
    public function setFormBudgetDotationSaisie( DotationSaisieForm $formBudgetDotationSaisie );



    /**
     * @return DotationSaisieFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormBudgetDotationSaisie();
}