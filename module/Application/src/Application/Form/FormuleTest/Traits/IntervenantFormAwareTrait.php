<?php

namespace Application\Form\FormuleTest\Traits;

use Application\Form\FormuleTest\IntervenantForm;

/**
 * Description of IntervenantFormAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantFormAwareTrait
{
    /**
     * @var IntervenantForm
     */
    protected $formFormuleTestIntervenant;



    /**
     * @param IntervenantForm $formFormuleTestIntervenant
     *
     * @return self
     */
    public function setFormFormuleTestIntervenant( IntervenantForm $formFormuleTestIntervenant )
    {
        $this->formFormuleTestIntervenant = $formFormuleTestIntervenant;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return IntervenantForm
     * @throws RuntimeException
     */
    public function getFormFormuleTestIntervenant() : IntervenantForm
    {
        if ($this->formFormuleTestIntervenant){
            return $this->formFormuleTestIntervenant;
        }else{
            return \Application::$container->get('FormElementManager')->get(IntervenantForm::class);
        }
    }
}