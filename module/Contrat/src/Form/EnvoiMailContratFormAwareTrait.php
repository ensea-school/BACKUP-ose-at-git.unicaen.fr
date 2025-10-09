<?php

namespace Contrat\Form;

/**
 * Description of EnvoiMailContratFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EnvoiMailContratFormAwareTrait
{
    protected ?EnvoiMailContratForm $formContratEnvoiMailContrat = null;



    /**
     * @param EnvoiMailContratForm $formContratEnvoiMailContrat
     *
     * @return self
     */
    public function setFormContratEnvoiMailContrat(?EnvoiMailContratForm $formContratEnvoiMailContrat)
    {
        $this->formContratEnvoiMailContrat = $formContratEnvoiMailContrat;

        return $this;
    }



    public function getFormContratEnvoiMailContrat(): ?EnvoiMailContratForm
    {
        if (!empty($this->formContratEnvoiMailContrat)) {
            return $this->formContratEnvoiMailContrat;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EnvoiMailContratForm::class);
    }
}