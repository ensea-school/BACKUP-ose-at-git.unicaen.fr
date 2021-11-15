<?php

namespace Plafond\Form;

/**
 * Description of PlafondFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondFormAwareTrait
{
    /**
     * @var PlafondForm
     */
    protected $formPlafond;



    /**
     * @param PlafondForm $formPlafond
     *
     * @return self
     */
    public function setFormPlafond(PlafondForm $formPlafond)
    {
        $this->formPlafond = $formPlafond;

        return $this;
    }



    /**
     * @return PlafondForm
     */
    public function getFormPlafond(): ?PlafondForm
    {
        if ($this->formPlafond) {
            return $this->formPlafond;
        } else {
            return \Application::$container->get('FormElementManager')->get(PlafondForm::class);
        }
    }
}