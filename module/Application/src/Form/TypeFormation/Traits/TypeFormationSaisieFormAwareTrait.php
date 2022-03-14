<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\TypeFormation\Traits;

use Application\Form\TypeFormation\TypeFormationSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait TypeFormationSaisieFormAwareTrait
{
    /**
     * @var TypeFormationSaisieForm
     */
    private $typeFormationSaisie;



    /**
     * @param TypeFormationSaisieForm $formTypeFormationSaisie
     *
     * @return self
     */
    public function setFormTypeFormationSaisie(TypeFormationSaisieForm $formTypeFormationSaisie)
    {
        $this->formTypeFormationSaisie = $formTypeFormationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeFormationSaisieForm
     */
    public function getFormTypeFormationSaisie()
    {
        if (!empty($this->formTypeFormationSaisie)) {
            return $this->formTypeFormationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeFormationSaisieForm::class);
    }
}