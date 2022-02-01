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
    protected ?TypeRessourceSaisieForm $formTypeRessourceTypeRessourceSaisie;



    /**
     * @param TypeRessourceSaisieForm|null $formTypeRessourceTypeRessourceSaisie
     *
     * @return self
     */
    public function setFormTypeRessourceTypeRessourceSaisie( ?TypeRessourceSaisieForm $formTypeRessourceTypeRessourceSaisie )
    {
        $this->formTypeRessourceTypeRessourceSaisie = $formTypeRessourceTypeRessourceSaisie;

        return $this;
    }



    public function getFormTypeRessourceTypeRessourceSaisie(): ?TypeRessourceSaisieForm
    {
        if (!$this->formTypeRessourceTypeRessourceSaisie){
            $this->formTypeRessourceTypeRessourceSaisie = \Application::$container->get('FormElementManager')->get(TypeRessourceSaisieForm::class);
        }

        return $this->formTypeRessourceTypeRessourceSaisie;
    }
}