<?php

namespace Lieu\Form;

/**
 * Description of PaysSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysSaisieFormAwareTrait
{
    protected ?PaysSaisieForm $formPaysSaisie = null;



    /**
     * @param PaysSaisieForm $formPaysSaisie
     *
     * @return self
     */
    public function setFormPaysSaisie(?PaysSaisieForm $formPaysSaisie)
    {
        $this->formPaysSaisie = $formPaysSaisie;

        return $this;
    }



    public function getFormPaysSaisie(): ?PaysSaisieForm
    {
        if (!empty($this->formPaysSaisie)) {
            return $this->formPaysSaisie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(PaysSaisieForm::class);
    }
}