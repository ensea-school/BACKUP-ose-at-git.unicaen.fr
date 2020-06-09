<?php

namespace Application\Form\TypeRessource\Traits;

use Application\Form\TypeRessource\TypeRessourceSaisieForm;

/**
 * Description of TypeRessourceSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceSaisieFormAwareTrait
{
    /**
     * @var TypeRessourceSaisieForm
     */
    private $formTypeRessourceSaisieForm;



    /**
     * @param TypeRessourceSaisieForm $formTypeRessourceSaisie
     *
     * @return self
     */
    public function setFormTypeRessourceSaisie(TypeRessourceSaisieForm $formTypeRessourceSaisie)
    {
        $this->formTypeRessourceSaisieForm = $formTypeRessourceSaisie;

        return $this;
    }



    /**
     *
     * @return TypeRessourceSaisieForm
     */
    public function getFormTypeRessourceSaisie()
    {
        if (!empty($this->formTypeRessourceSaisie)) {
            return $this->formTypeRessourceSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeRessourceSaisieForm::class);
    }
}
