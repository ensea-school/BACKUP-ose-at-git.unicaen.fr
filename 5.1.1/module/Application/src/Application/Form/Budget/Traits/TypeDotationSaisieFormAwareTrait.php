<?php

namespace Application\Form\Budget\Traits;

use Application\Form\Budget\TypeDotationSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of TypeDotationSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeDotationSaisieFormAwareTrait
{
    /**
     * @var TypeDotationSaisieForm
     */
    private $formTypeDotationSaisie;





    /**
     * @param TypeDotationSaisieForm $formTypeDotationSaisie
     * @return self
     */
    public function setFormTypeDotationSaisie( TypeDotationSaisieForm $formTypeDotationSaisie )
    {
        $this->formTypeDotationSaisie = $formTypeDotationSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeDotationSaisieForm
     * @throws RuntimeException
     */
    public function getFormTypeDotationSaisie()
    {
        if (!empty($this->formTypeDotationSaisie)){
            return $this->formTypeDotationSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('TypeDotationSaisie');
    }
}