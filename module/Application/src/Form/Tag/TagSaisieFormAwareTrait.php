<?php

namespace Application\Form\Tag;


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

        return \Application::$container->get('FormElementManager')->get(TagSaisieForm::class);
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