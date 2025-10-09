<?php

namespace Paiement\Form\DomaineFonctionnel;


/**
 * Description of DomaineFonctionnelSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DomaineFonctionnelSaisieFormAwareTrait
{
    protected ?DomaineFonctionnelSaisieForm $formDomaineFonctionnelDomaineFonctionnelSaisie = null;



    /**
     * @param DomaineFonctionnelSaisieForm $formDomaineFonctionnelDomaineFonctionnelSaisie
     *
     * @return self
     */
    public function setFormDomaineFonctionnelDomaineFonctionnelSaisie(?DomaineFonctionnelSaisieForm $formDomaineFonctionnelDomaineFonctionnelSaisie)
    {
        $this->formDomaineFonctionnelDomaineFonctionnelSaisie = $formDomaineFonctionnelDomaineFonctionnelSaisie;

        return $this;
    }



    public function getFormDomaineFonctionnelDomaineFonctionnelSaisie(): ?DomaineFonctionnelSaisieForm
    {
        if (!empty($this->formDomaineFonctionnelDomaineFonctionnelSaisie)) {
            return $this->formDomaineFonctionnelDomaineFonctionnelSaisie;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DomaineFonctionnelSaisieForm::class);
    }
}