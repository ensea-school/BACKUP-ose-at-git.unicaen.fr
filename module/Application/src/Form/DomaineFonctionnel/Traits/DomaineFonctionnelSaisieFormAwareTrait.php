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
    protected ?DomaineFonctionnelSaisieForm $formDomaineFonctionnelDomaineFonctionnelSaisie;



    /**
     * @param DomaineFonctionnelSaisieForm|null $formDomaineFonctionnelDomaineFonctionnelSaisie
     *
     * @return self
     */
    public function setFormDomaineFonctionnelDomaineFonctionnelSaisie( ?DomaineFonctionnelSaisieForm $formDomaineFonctionnelDomaineFonctionnelSaisie )
    {
        $this->formDomaineFonctionnelDomaineFonctionnelSaisie = $formDomaineFonctionnelDomaineFonctionnelSaisie;

        return $this;
    }



    public function getFormDomaineFonctionnelDomaineFonctionnelSaisie(): ?DomaineFonctionnelSaisieForm
    {
        if (!$this->formDomaineFonctionnelDomaineFonctionnelSaisie){
            $this->formDomaineFonctionnelDomaineFonctionnelSaisie = \Application::$container->get('FormElementManager')->get(DomaineFonctionnelSaisieForm::class);
        }

        return $this->formDomaineFonctionnelDomaineFonctionnelSaisie;
    }
}