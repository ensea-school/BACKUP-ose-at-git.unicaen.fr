<?php

namespace Mission\Form;


/**
 * Description of OffreEmploiFormAwareTrait
 *
 * @author UnicaenCode
 */
trait OffreEmploiFormAwareTrait
{
    protected ?OffreEmploiForm $formOffreEmploi = null;



    /**
     * @param OffreEmploiForm $formOffreEmploi
     *
     * @return self
     */
    public function setFormOffreEmploi(?OffreEmploiForm $formOffreEmploi)
    {
        $this->formOffreEmploi = $formOffreEmploi;

        return $this;
    }



    public function getFormOffreEmploi(): ?OffreEmploiForm
    {
        if (!empty($this->formOffreEmploi)) {
            return $this->formOffreEmploi;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(OffreEmploiForm::class);
    }
}