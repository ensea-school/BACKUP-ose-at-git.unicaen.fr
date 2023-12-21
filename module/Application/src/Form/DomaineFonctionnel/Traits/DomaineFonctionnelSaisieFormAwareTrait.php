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

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(DomaineFonctionnelSaisieForm::class);
    }
}