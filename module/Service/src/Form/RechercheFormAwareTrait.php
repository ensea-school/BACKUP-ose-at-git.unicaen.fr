<?php

namespace Service\Form;

/**
 * Description of RechercheFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheFormAwareTrait
{
    protected ?RechercheForm $formServiceRecherche = null;



    /**
     * @param RechercheForm $formServiceRecherche
     *
     * @return self
     */
    public function setFormServiceRecherche(?RechercheForm $formServiceRecherche)
    {
        $this->formServiceRecherche = $formServiceRecherche;

        return $this;
    }



    public function getFormServiceRecherche(): ?RechercheForm
    {
        if (!empty($this->formServiceRecherche)) {
            return $this->formServiceRecherche;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(RechercheForm::class);
    }
}