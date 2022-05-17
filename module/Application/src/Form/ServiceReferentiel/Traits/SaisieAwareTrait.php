<?php

namespace Application\Form\ServiceReferentiel\Traits;

use Application\Form\ServiceReferentiel\Saisie;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    protected ?Saisie $formServiceReferentielSaisie = null;



    /**
     * @param Saisie $formServiceReferentielSaisie
     *
     * @return self
     */
    public function setFormServiceReferentielSaisie(?Saisie $formServiceReferentielSaisie)
    {
        $this->formServiceReferentielSaisie = $formServiceReferentielSaisie;

        return $this;
    }



    public function getFormServiceReferentielSaisie(): ?Saisie
    {
        if (!empty($this->formServiceReferentielSaisie)) {
            return $this->formServiceReferentielSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(Saisie::class);
    }
}