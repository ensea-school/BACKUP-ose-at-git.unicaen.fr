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
    protected ?ParametresForm $formParametres;



    /**
     * @param ParametresForm|null $formParametres
     *
     * @return self
     */
    public function setFormParametres( ?ParametresForm $formParametres )
    {
        $this->formParametres = $formParametres;

        return $this;
    }



    public function getFormParametres(): ?ParametresForm
    {
        if (!$this->formParametres){
            $this->formParametres = \Application::$container->get('FormElementManager')->get(ParametresForm::class);
        }

        return $this->formParametres;
    }
}