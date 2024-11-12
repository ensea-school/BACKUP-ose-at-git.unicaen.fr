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
    public function setFormPlafond(?PlafondForm $formPlafond)
    {
        $this->formPlafond = $formPlafond;

        return $this;
    }



    public function getFormPlafond(): ?PlafondForm
    {
        if (!empty($this->formPlafond)) {
            return $this->formPlafond;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(PlafondForm::class);
    }
}