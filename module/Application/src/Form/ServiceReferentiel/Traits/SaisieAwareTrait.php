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
    protected ?Saisie $formServiceReferentielSaisie;



    /**
     * @param Saisie|null $formServiceReferentielSaisie
     *
     * @return self
     */
    public function setFormServiceReferentielSaisie( ?Saisie $formServiceReferentielSaisie )
    {
        $this->formServiceReferentielSaisie = $formServiceReferentielSaisie;

        return $this;
    }



    public function getFormServiceReferentielSaisie(): ?Saisie
    {
        if (!$this->formServiceReferentielSaisie){
            $this->formServiceReferentielSaisie = \Application::$container->get('FormElementManager')->get(Saisie::class);
        }

        return $this->formServiceReferentielSaisie;
    }
}