<?php

namespace Application\Form\Etablissement\Traits;

use Application\Form\Etablissement\EtablissementSaisieForm;

/**
 * Description of EtablissementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementSaisieFormAwareTrait
{
    protected ?EtablissementSaisieForm $formEtablissementEtablissementSaisie = null;



    /**
     * @param EtablissementSaisieForm $formEtablissementEtablissementSaisie
     *
     * @return self
     */
    public function setFormEtablissementEtablissementSaisie(?EtablissementSaisieForm $formEtablissementEtablissementSaisie)
    {
        $this->formEtablissementEtablissementSaisie = $formEtablissementEtablissementSaisie;

        return $this;
    }



    public function getFormEtablissementEtablissementSaisie(): ?EtablissementSaisieForm
    {
        if (!empty($this->formEtablissementEtablissementSaisie)) {
            return $this->formEtablissementEtablissementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(EtablissementSaisieForm::class);
    }
}