<?php

namespace Application\Form\Pays\Traits;

use Application\Form\Pays\PaysSaisieForm;

/**
 * Description of PaysSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PaysSaisieFormAwareTrait
{
    protected ?PaysSaisieForm $formPaysPaysSaisie = null;



    /**
     * @param PaysSaisieForm $formPaysPaysSaisie
     *
     * @return self
     */
    public function setFormPaysPaysSaisie(?PaysSaisieForm $formPaysPaysSaisie)
    {
        $this->formPaysPaysSaisie = $formPaysPaysSaisie;

        return $this;
    }



    public function getFormPaysPaysSaisie(): ?PaysSaisieForm
    {
        if (!empty($this->formPaysPaysSaisie)) {
            return $this->formPaysPaysSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(PaysSaisieForm::class);
    }
}