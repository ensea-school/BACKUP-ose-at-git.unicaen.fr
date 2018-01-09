<?php

namespace Application\Form\Budget\Traits;

use Application\Form\Budget\TypeDotationSaisieForm;

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
     *
     * @return self
     */
    public function setFormTypeDotationSaisie(TypeDotationSaisieForm $formTypeDotationSaisie)
    {
        $this->formTypeDotationSaisie = $formTypeDotationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeDotationSaisieForm
     */
    public function getFormTypeDotationSaisie()
    {
        if (!empty($this->formTypeDotationSaisie)) {
            return $this->formTypeDotationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeDotationSaisieForm::class);
    }
}