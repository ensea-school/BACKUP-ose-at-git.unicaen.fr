<?php

namespace EtatSortie\Form;

/**
 * Description of EtatSortieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatSortieFormAwareTrait
{
    protected ?EtatSortieForm $formEtatSortie = null;



    /**
     * @param EtatSortieForm $formEtatSortie
     *
     * @return self
     */
    public function setFormEtatSortie(?EtatSortieForm $formEtatSortie)
    {
        $this->formEtatSortie = $formEtatSortie;

        return $this;
    }



    public function getFormEtatSortie(): ?EtatSortieForm
    {
        if (!empty($this->formEtatSortie)) {
            return $this->formEtatSortie;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(EtatSortieForm::class);
    }
}