<?php

namespace Plafond\Form;


/**
 * Description of PlafondFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondFormAwareTrait
{
    protected ?PlafondForm $formPlafond;



    /**
     * @param PlafondForm|null $formPlafond
     *
     * @return self
     */
    public function setFormPlafond( ?PlafondForm $formPlafond )
    {
        $this->formPlafond = $formPlafond;

        return $this;
    }



    public function getFormPlafond(): ?PlafondForm
    {
        if (!$this->formPlafond){
            $this->formPlafond = \Application::$container->get('FormElementManager')->get(PlafondForm::class);
        }

        return $this->formPlafond;
    }
}