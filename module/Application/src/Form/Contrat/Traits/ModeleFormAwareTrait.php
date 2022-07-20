<?php

namespace Application\Form\Contrat\Traits;

use Application\Form\Contrat\ModeleForm;

/**
 * Description of ModeleFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ModeleFormAwareTrait
{
    protected ?ModeleForm $formContratModele = null;



    /**
     * @param ModeleForm $formContratModele
     *
     * @return self
     */
    public function setFormContratModele(?ModeleForm $formContratModele)
    {
        $this->formContratModele = $formContratModele;

        return $this;
    }



    public function getFormContratModele(): ?ModeleForm
    {
        if (!empty($this->formContratModele)) {
            return $this->formContratModele;
        }

        return \Application::$container->get('FormElementManager')->get(ModeleForm::class);
    }
}