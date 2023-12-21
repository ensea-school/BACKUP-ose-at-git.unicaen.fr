<?php

namespace Application\Form\Corps\Traits;

use Application\Form\Corps\CorpsSaisieForm;

/**
 * Description of CorpsSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CorpsSaisieFormAwareTrait
{
    protected ?CorpsSaisieForm $formCorpsCorpsSaisie = null;



    /**
     * @param CorpsSaisieForm $formCorpsCorpsSaisie
     *
     * @return self
     */
    public function setFormCorpsCorpsSaisie(?CorpsSaisieForm $formCorpsCorpsSaisie)
    {
        $this->formCorpsCorpsSaisie = $formCorpsCorpsSaisie;

        return $this;
    }



    public function getFormCorpsCorpsSaisie(): ?CorpsSaisieForm
    {
        if (!empty($this->formCorpsCorpsSaisie)) {
            return $this->formCorpsCorpsSaisie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(CorpsSaisieForm::class);
    }
}