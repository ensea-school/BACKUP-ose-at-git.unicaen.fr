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
    protected ?EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie;



    /**
     * @param EtapeModulateursSaisie|null $formOffreFormationEtapeModulateursSaisie
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
        if (!$this->formOffreFormationEtapeModulateursSaisie){
            $this->formOffreFormationEtapeModulateursSaisie = \Application::$container->get('FormElementManager')->get(EtapeModulateursSaisie::class);
        }

        return $this->formOffreFormationEtapeModulateursSaisie;
    }
}