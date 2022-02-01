<?php

namespace Plafond\Form;


/**
 * Description of PlafondFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondFormAwareTrait
{
    protected ?PlafondForm $formPlafond = null;



    /**
     * @param PlafondForm $formPlafond
     *
     * @return self
     */
    public function setFormPlafond( PlafondForm $formPlafond )
    {
        $this->formPlafond = $formPlafond;

        return $this;
    }



    public function getFormPlafond(): ?PlafondForm
    {
        if (empty($this->formPlafond)){
            $this->formPlafond = \Application::$container->get('FormElementManager')->get(PlafondForm::class);
        }

        return $this->formPlafond;
    }
}