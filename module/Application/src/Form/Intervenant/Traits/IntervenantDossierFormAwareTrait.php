<?php

namespace Application\Form\Intervenant\Traits;

use Application\Entity\Db\Intervenant;
use Application\Form\Intervenant\IntervenantDossierForm;

/**
 * Description of IntervenantDossierFormAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantDossierFormAwareTrait
{
    /**
     * @var IntervenantDossierForm
     */
    private $intervenantDossierForm;


    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return IntervenantDossierForm
     */
    public function getIntervenantDossierForm(Intervenant $intervenant)
    {
        if (!empty($this->intervenantDossierForm)) {
            return $this->intervenantDossierForm;
        }

        return \Application::$container->get('FormElementManager')->get(IntervenantDossierForm::class,['intervenant' => $intervenant]);
    }
}