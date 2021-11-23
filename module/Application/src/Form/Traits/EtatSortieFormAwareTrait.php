<?php

namespace Application\Form\Traits;

use Application\Form\EtatSortieForm;

/**
 * Description of EtatSortieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatSortieFormAwareTrait
{
    /**
     * @var EtatSortieForm
     */
    protected $formEtatSortie;



    /**
     * @param EtatSortieForm $formContratModele
     *
     * @return self
     */
    public function setFormEtatSortie( ModeleForm $formEtatSortie )
    {
        $this->formEtatSortie = $formEtatSortie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtatSortieForm
     * @throws RuntimeException
     */
    public function getFormEtatSortie() : EtatSortieForm
    {
        if ($this->formEtatSortie){
            return $this->formEtatSortie;
        }else{
            return \Application::$container->get('FormElementManager')->get(EtatSortieForm::class);
        }
    }
}