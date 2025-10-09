<?php

namespace OffreFormation\Form\Traits;

use OffreFormation\Form\ElementModulateurCentreCoutTauxRemuForm;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Description of ElementModulateurCentreCoutTauxRemuFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurCentreCoutTauxRemuFormAwareTrait
{
    protected ?ElementModulateurCentreCoutTauxRemuForm $formElementModulateurCentreCoutTauxRemu = null;



    /**
     * @param ElementModulateurCentreCoutTauxRemuForm $formElementModulateurCentreCoutTauxRemu
     *
     * @return self
     */
    public function setFormElementModulateurCentreCout(?ElementModulateurCentreCoutTauxRemuForm $formElementModulateurCentreCoutTauxRemu)
    {
        $this->formElementModulateurCentreCoutTauxRemu = $formElementModulateurCentreCoutTauxRemu;

        return $this;
    }



    /**
     * @return ElementModulateurCentreCoutTauxRemuForm|null
     *
     *
     */
    public function getFormElementModulateurCentreCoutTauxRemu(): ?ElementModulateurCentreCoutTauxRemuForm
    {
        if (!empty($this->formElementModulateurCentreCoutTauxRemu)) {
            return $this->formElementModulateurCentreCoutTauxRemu;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ElementModulateurCentreCoutTauxRemuForm::class);
    }
}