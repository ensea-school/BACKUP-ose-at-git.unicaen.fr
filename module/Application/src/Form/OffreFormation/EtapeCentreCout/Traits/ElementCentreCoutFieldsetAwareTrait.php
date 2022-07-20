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
    protected ?ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout = null;



    /**
     * @param ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout
     *
     * @return self
     */
    public function setFieldsetOffreFormationEtapeCentreCoutElementCentreCout(?ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout)
    {
        $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout = $fieldsetOffreFormationEtapeCentreCoutElementCentreCout;

        return $this;
    }



    public function getFieldsetOffreFormationEtapeCentreCoutElementCentreCout(): ?ElementCentreCoutFieldset
    {
        if (!empty($this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout)) {
            return $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout;
        }

        return \Application::$container->get('FormElementManager')->get(ElementCentreCoutFieldset::class);
    }
}