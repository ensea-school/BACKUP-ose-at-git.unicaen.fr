<?php

namespace Application\Form\Traits;

use Application\Form\ParametresForm;

/**
 * Description of ParametresFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresFormAwareTrait
{
    /**
     * @var ParametresForm
     */
    private $formParametres;



    /**
     * @param ParametresForm $formParametres
     *
     * @return self
     */
    public function setFormParametres(ParametresForm $formParametres)
    {
        $this->formParametres = $formParametres;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ParametresForm
     */
    public function getFormParametres()
    {
        if (!empty($this->formParametres)) {
            return $this->formParametres;
        }

        return \Application::$container->get('FormElementManager')->get('Parametres');
    }
}