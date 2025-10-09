<?php

namespace Administration\Form;

/**
 * Description of ParametresFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ParametresFormAwareTrait
{
    protected ?ParametresForm $formParametres = null;



    /**
     * @param ParametresForm $formParametres
     *
     * @return self
     */
    public function setFormParametres(?ParametresForm $formParametres)
    {
        $this->formParametres = $formParametres;

        return $this;
    }



    public function getFormParametres(): ?ParametresForm
    {
        if (!empty($this->formParametres)) {
            return $this->formParametres;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ParametresForm::class);
    }
}