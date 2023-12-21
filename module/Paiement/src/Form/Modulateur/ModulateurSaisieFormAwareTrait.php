<?php

namespace Paiement\Form\Modulateur;


/**
 * Description of ModulateurSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModulateurSaisieFormAwareTrait
{
    protected ?ModulateurSaisieForm $formModulateurModulateurSaisie = null;



    /**
     * @param ModulateurSaisieForm $formModulateurModulateurSaisie
     *
     * @return self
     */
    public function setFormModulateurModulateurSaisie(?ModulateurSaisieForm $formModulateurModulateurSaisie)
    {
        $this->formModulateurModulateurSaisie = $formModulateurModulateurSaisie;

        return $this;
    }



    public function getFormModulateurModulateurSaisie(): ?ModulateurSaisieForm
    {
        if (!empty($this->formModulateurModulateurSaisie)) {
            return $this->formModulateurModulateurSaisie;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(ModulateurSaisieForm::class);
    }
}