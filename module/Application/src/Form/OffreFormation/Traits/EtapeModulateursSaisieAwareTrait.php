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
    protected ?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie = null;



    /**
     * @param EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie
     *
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie( ?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie )
    {
        $this->formOffreFormationEtapeModulateursSaisie = $formOffreFormationEtapeModulateursSaisie;

        return $this;
    }



    public function getFormOffreFormationEtapeModulateursSaisie(): ?EtapeModulateursSaisie
    {
        if (empty($this->formOffreFormationEtapeModulateursSaisie)){
            $this->formOffreFormationEtapeModulateursSaisie = \Application::$container->get('FormElementManager')->get(EtapeModulateursSaisie::class);
        }

        return $this->formOffreFormationEtapeModulateursSaisie;
    }
}