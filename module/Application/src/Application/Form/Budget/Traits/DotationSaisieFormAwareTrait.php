<?php

namespace Application\Form\Budget\Traits;

use Application\Form\Budget\DotationSaisieForm;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setFormBudgetDotationSaisie( DotationSaisieForm $formBudgetDotationSaisie )
    {
        $this->formBudgetDotationSaisie = $formBudgetDotationSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DotationSaisieForm
     * @throws RuntimeException
     */
    public function getFormBudgetDotationSaisie()
    {
        if (!empty($this->formBudgetDotationSaisie)){
            return $this->formBudgetDotationSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('BudgetDotationSaisie');
    }
}