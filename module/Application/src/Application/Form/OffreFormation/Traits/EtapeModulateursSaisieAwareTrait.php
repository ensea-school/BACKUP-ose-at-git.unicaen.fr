<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\EtapeModulateursSaisie;

/**
 * Description of EtapeModulateursSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeModulateursSaisieAwareTrait
{
    /**
     * @var EtapeModulateursSaisie
     */
    private $formOffreFormationEtapeModulateursSaisie;



    /**
     * @param EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie(EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie)
    {
        $this->formOffreFormationEtapeModulateursSaisie = $formOffreFormationEtapeModulateursSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeModulateursSaisie
     */
    public function getFormOffreFormationEtapeModulateursSaisie()
    {
        if (!empty($this->formOffreFormationEtapeModulateursSaisie)) {
            return $this->formOffreFormationEtapeModulateursSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('EtapeModulateursSaisie');
    }
}