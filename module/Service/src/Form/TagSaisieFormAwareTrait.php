<?php

namespace Service\Form;


/**
 * Description of TagSaisieFormAwareTrait
 *
 */
trait TagSaisieFormAwareTrait
{
    protected ?TagSaisieForm $formTag = null;



    public function getFormTag (): ?TagSaisieForm
    {
        if (!empty($this->formTag)) {
            return $this->formTag;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(TagSaisieForm::class);
    }



    /**
     * @param TagSaisieForm $formTag
     *
     * @return self
     */
    public function setFormTag (?TagSaisieForm $formTag)
    {
        $this->formTag = $formTag;

        return $this;
    }
}