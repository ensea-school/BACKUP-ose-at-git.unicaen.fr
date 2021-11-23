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
    /**
     * @var ModeleForm
     */
    protected $formContratModele;



    /**
     * @param ModeleForm $formContratModele
     *
     * @return self
     */
    public function setFormContratModele( ModeleForm $formContratModele )
    {
        $this->formContratModele = $formContratModele;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ModeleForm
     * @throws RuntimeException
     */
    public function getFormContratModele() : ModeleForm
    {
        if ($this->formContratModele){
            return $this->formContratModele;
        }else{
            return \Application::$container->get('FormElementManager')->get(ModeleForm::class);
        }
    }
}