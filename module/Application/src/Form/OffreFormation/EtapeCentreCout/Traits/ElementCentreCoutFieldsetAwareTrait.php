<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Traits;

use Application\Form\OffreFormation\EtapeCentreCout\ElementCentreCoutFieldset;

/**
 * Description of ElementCentreCoutFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementCentreCoutFieldsetAwareTrait
{
    protected ?ElementCentreCoutFieldset $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset = null;



    /**
     * @param ElementCentreCoutFieldset $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutElementCentreCoutFieldset( ElementCentreCoutFieldset $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset )
    {
        $this->formOffreFormationEtapeCentreCoutElementCentreCoutFieldset = $formOffreFormationEtapeCentreCoutElementCentreCoutFieldset;

        return $this;
    }



    public function getFormOffreFormationEtapeCentreCoutElementCentreCoutFieldset(): ?ElementCentreCoutFieldset
    {
        if (empty($this->formOffreFormationEtapeCentreCoutElementCentreCoutFieldset)){
            $this->formOffreFormationEtapeCentreCoutElementCentreCoutFieldset = \Application::$container->get('FormElementManager')->get(ElementCentreCoutFieldset::class);
        }

        return $this->formOffreFormationEtapeCentreCoutElementCentreCoutFieldset;
    }
}