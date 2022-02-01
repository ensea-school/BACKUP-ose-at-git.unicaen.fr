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
    protected ?EtatSortieForm $formEtatSortie = null;



    /**
     * @param EtatSortieForm $formEtatSortie
     *
     * @return self
     */
    public function setFormEtatSortie( EtatSortieForm $formEtatSortie )
    {
        $this->formEtatSortie = $formEtatSortie;

        return $this;
    }



    public function getFormEtatSortie(): ?EtatSortieForm
    {
        if (empty($this->formEtatSortie)){
            $this->formEtatSortie = \Application::$container->get('FormElementManager')->get(EtatSortieForm::class);
        }

        return $this->formEtatSortie;
    }
}