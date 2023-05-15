<?php

namespace Paiement\Form\TypeRessource\Traits;

use Paiement\Form\TypeRessource\TypeRessourceSaisieForm;

/**
 * Description of TypeRessourceSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeRessourceSaisieFormAwareTrait
{
    protected ?TypeRessourceSaisieForm $formTypeRessourceTypeRessourceSaisie = null;



    /**
     * @param TypeRessourceSaisieForm $formTypeRessourceTypeRessourceSaisie
     *
     * @return self
     */
    public function setFormTypeRessourceTypeRessourceSaisie(?TypeRessourceSaisieForm $formTypeRessourceTypeRessourceSaisie)
    {
        $this->formTypeRessourceTypeRessourceSaisie = $formTypeRessourceTypeRessourceSaisie;

        return $this;
    }



    public function getFormTypeRessourceTypeRessourceSaisie(): ?TypeRessourceSaisieForm
    {
        if (!empty($this->formTypeRessourceTypeRessourceSaisie)) {
            return $this->formTypeRessourceTypeRessourceSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeRessourceSaisieForm::class);
    }
}