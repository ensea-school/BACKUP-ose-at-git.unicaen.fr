<?php

namespace Application\Form\DomaineFonctionnel\Traits;

use Application\Form\DomaineFonctionnel\DomaineFonctionnelSaisieForm;

/**
 * Description of DomaineFonctionnelSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelSaisieFormAwareTrait
{
    /**
     * @var DomaineFonctionnelSaisieForm
     */
    private $formDomaineFonctionnelSaisie;



    /**
     * @param DomaineFonctionnelSaisieForm $formDomaineFonctionnelSaisie
     *
     * @return self
     */
    public function setFormDomaineFonctionnelSaisie(DomaineFonctionnelSaisieForm $formDomaineFonctionnelSaisie)
    {
        $this->formDomaineFonctionnelSaisie = $formDomaineFonctionnelSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DomaineFonctionnelSaisieForm
     */
    public function getFormDomaineFonctionnelSaisie()
    {
        if (!empty($this->formDomaineFonctionnelSaisie)) {
            return $this->formDomaineFonctionnelSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(DomaineFonctionnelSaisieForm::class);
    }
}
