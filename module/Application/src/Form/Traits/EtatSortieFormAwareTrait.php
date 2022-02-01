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
    protected ?EtatSortieForm $formEtatSortie;



    /**
     * @param EtatSortieForm|null $formEtatSortie
     *
     * @return self
     */
    public function setFormEtatSortie( ?EtatSortieForm $formEtatSortie )
    {
        $this->formEtatSortie = $formEtatSortie;

        return $this;
    }



    public function getFormEtatSortie(): ?EtatSortieForm
    {
        if (!$this->formEtatSortie){
            $this->formEtatSortie = \Application::$container->get('FormElementManager')->get(EtatSortieForm::class);
        }

        return $this->formEtatSortie;
    }
}