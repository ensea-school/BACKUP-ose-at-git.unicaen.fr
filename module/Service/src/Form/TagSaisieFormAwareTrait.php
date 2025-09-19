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

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TagSaisieForm::class);
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