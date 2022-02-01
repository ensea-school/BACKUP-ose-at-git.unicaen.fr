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
    protected ?ModeleForm $formContratModele;



    /**
     * @param ModeleForm|null $formContratModele
     *
     * @return self
     */
    public function setFormContratModele( ?ModeleForm $formContratModele )
    {
        $this->formContratModele = $formContratModele;

        return $this;
    }



    public function getFormContratModele(): ?ModeleForm
    {
        if (!$this->formContratModele){
            $this->formContratModele = \Application::$container->get('FormElementManager')->get(ModeleForm::class);
        }

        return $this->formContratModele;
    }
}