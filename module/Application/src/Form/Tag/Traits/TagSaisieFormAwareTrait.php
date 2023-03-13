<?php

namespace Application\Form\Tag\Traits;

use Application\Entity\Db\Tag;
use Application\Form\Tag\TagSaisieForm;

/**
 * Description of TagSaisieFormAwareTrait
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
trait TagSaisieFormAwareTrait
{
    protected ?TagSaisieForm $formTagSaisie = null;


    /**
     * @param TagSaisieForm $formTagSaisie
     *
     * @return self
     */
    public function setFormTagSaisie(?TagSaisieForm $formTagSaisie)
    {
        $this->formTagSaisie = $formTagSaisie;

        return $this;
    }


    public function getFormTagSaisie(): ?TagSaisieForm
    {
        if (!empty($this->formTagSaisie)) {
            return $this->formTagSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TagSaisieForm::class);
    }
}