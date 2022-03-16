<?php
/*
* @author JORIOT Florian <florian.joriot at unicaen.fr>
*/

namespace Application\Form\Departement\Traits;

use Application\Form\Departement\DepartementSaisieForm;

/**
 * Description of GradeSaisieFormAwareTrait
 */
trait DepartementSaisieFormAwareTrait
{
    /**
     * @var DepartementSaisieForm
     */
    private $departementSaisie;



    /**
     * @param DepartementSaisieForm $departementSaisie
     *
     * @return self
     */
    public function setFormGroupeTypeFormationSaisie(DepartementSaisieForm $departementSaisie)
    {
        $this->formdepartementSaisie = $departementSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DepartementSaisieForm
     */
    public function getFormDepartementSaisie(): DepartementSaisieForm
    {
        if (!empty($this->formDepartementSaisie)) {
            return $this->formDepartementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(DepartementSaisieForm::class);
    }
}