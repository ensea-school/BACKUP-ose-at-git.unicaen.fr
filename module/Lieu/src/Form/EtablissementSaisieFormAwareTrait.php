<?php

namespace Lieu\Form;

/**
 * Description of EtablissementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementSaisieFormAwareTrait
{
    protected ?EtablissementSaisieForm $formEtablissementSaisie = null;



    /**
     * @param EtablissementSaisieForm $formEtablissementSaisie
     *
     * @return self
     */
    public function setFormEtablissementSaisie(?EtablissementSaisieForm $formEtablissementSaisie)
    {
        $this->formEtablissementSaisie = $formEtablissementSaisie;

        return $this;
    }



    public function getFormEtablissementSaisie(): ?EtablissementSaisieForm
    {
        if (!empty($this->formEtablissementSaisie)) {
            return $this->formEtablissementSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtablissementSaisieForm::class);
    }
}