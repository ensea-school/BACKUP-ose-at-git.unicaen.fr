<?php

namespace Application\Form\Contrat\Traits;


use Application\Form\Contrat\EnvoiMailContratForm;

trait EnvoiMailContratFormAwareTrait
{
    /**
     * @var EnvoiMailContratForm
     */
    protected $formEnvoiMailContrat;



    /**
     * Retourne un nouveau formulaire d'envoi de contrat
     *
     * @return EnvoiMailContratForm
     */
    public function getFormEnvoiMailContrat(): EnvoiMailContratForm
    {
        if ($this->formEnvoiMailContrat) {
            return $this->formEnvoiMailContrat;
        } else {
            return \Application::$container->get('FormElementManager')->get(EnvoiMailContratForm::class);
        }
    }
}