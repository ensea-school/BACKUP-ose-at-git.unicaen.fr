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
    /**
     * @var Saisie
     */
    private $formServiceReferentielSaisie;



    /**
     * @param Saisie $formServiceReferentielSaisie
     *
     * @return self
     */
    public function setFormServiceReferentielSaisie(Saisie $formServiceReferentielSaisie)
    {
        $this->formServiceReferentielSaisie = $formServiceReferentielSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     */
    public function getFormServiceReferentielSaisie()
    {
        if (!empty($this->formServiceReferentielSaisie)) {
            return $this->formServiceReferentielSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('ServiceReferentielSaisie');
    }
}