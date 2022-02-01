<?php

namespace Application\Form\Contrat\Traits;

use Application\Form\Contrat\EnvoiMailContratForm;

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
    public function setFormContratEnvoiMailContrat( EnvoiMailContratForm $formContratEnvoiMailContrat )
    {
        $this->formContratEnvoiMailContrat = $formContratEnvoiMailContrat;

        return $this;
    }



    public function getFormContratEnvoiMailContrat(): ?EnvoiMailContratForm
    {
        if (empty($this->formContratEnvoiMailContrat)){
            $this->formContratEnvoiMailContrat = \Application::$container->get('FormElementManager')->get(EnvoiMailContratForm::class);
        }

        return $this->formContratEnvoiMailContrat;
    }
}