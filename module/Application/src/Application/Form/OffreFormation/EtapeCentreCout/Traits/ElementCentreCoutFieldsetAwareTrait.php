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
    /**
     * @var ElementCentreCoutFieldset
     */
    private $fieldsetOffreFormationEtapeCentreCoutElementCentreCout;



    /**
     * @param ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout
     *
     * @return self
     */
    public function setFieldsetOffreFormationEtapeCentreCoutElementCentreCout(ElementCentreCoutFieldset $fieldsetOffreFormationEtapeCentreCoutElementCentreCout)
    {
        $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout = $fieldsetOffreFormationEtapeCentreCoutElementCentreCout;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ElementCentreCoutFieldset
     */
    public function getFieldsetOffreFormationEtapeCentreCoutElementCentreCout()
    {
        if (!empty($this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout)) {
            return $this->fieldsetOffreFormationEtapeCentreCoutElementCentreCout;
        }

        return \Application::$container->get('FormElementManager')->get('ElementCentreCoutFieldset');
    }
}