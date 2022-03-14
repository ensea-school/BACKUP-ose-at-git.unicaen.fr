<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\GroupeTypeFormation\Traits;

use Application\Form\GroupeTypeFormation\GroupeTypeFormationSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait GroupeTypeFormationSaisieFormAwareTrait
{
    /**
     * @var GroupeTypeFormationSaisieForm
     */
    private $groupeTypeFormationSaisie;



    /**
     * @param GroupeTypeFormationSaisieForm $groupeTypeFormationSaisie
     *
     * @return self
     */
    public function setFormGroupeTypeFormationSaisie(GroupeTypeFormationSaisieForm $groupeTypeFormationSaisie)
    {
        $this->formGroupeTypeFormationSaisie = $groupeTypeFormationSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return GroupeTypeFormationSaisieForm
     */
    public function getFormGroupeTypeFormationSaisie()
    {
        if (!empty($this->formGroupeTypeFormationSaisie)) {
            return $this->formGroupeTypeFormationSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(GroupeTypeFormationSaisieForm::class);
    }
}