<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\EtapeSaisie;

/**
 * Description of EtapeSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeSaisieAwareTrait
{
    /**
     * @var EtapeSaisie
     */
    private $formOffreFormationEtapeSaisie;



    /**
     * @param EtapeSaisie $formOffreFormationEtapeSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeSaisie(EtapeSaisie $formOffreFormationEtapeSaisie)
    {
        $this->formOffreFormationEtapeSaisie = $formOffreFormationEtapeSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeSaisie
     */
    public function getFormOffreFormationEtapeSaisie()
    {
        if (!empty($this->formOffreFormationEtapeSaisie)) {
            return $this->formOffreFormationEtapeSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('EtapeSaisie');
    }
}