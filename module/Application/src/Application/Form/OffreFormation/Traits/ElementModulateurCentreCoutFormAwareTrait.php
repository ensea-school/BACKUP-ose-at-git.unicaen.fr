<?php

namespace Application\Form\OffreFormation\Traits;


use Application\Form\OffreFormation\ElementModulateurCentreCoutForm;

/**
 * Description of ElementModulateurCentreCoutAwareTrait
 *
 * @author UnicaenCode
 */
trait ElementModulateurCentreCoutFormAwareTrait
{
    /**
     * @var ElementModulateurCentreCoutForm
     */
    private $elementModulateurCentreCoutForm;



    /**
     * @param ElementModulateurCentreCoutForm $elementModulateurCentreCoutForm
     *
     * @return self
     */
    public function setElementModulateurCentreCoutForm(ElementModulateurCentreCoutForm $elementModulateurCentreCoutForm)
    {
        $this->elementModulateurCentreCoutForm = $elementModulateurCentreCoutForm;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire
     *
     * @return ElementModulateurCentreCoutForm
     */
    public function getElementModulateurCentreCoutForm()
    {
        if (!empty($this->elementModulateurCentreCoutForm)) {
            return $this->elementModulateurCentreCoutForm;
        }

        return \Application::$container->get('FormElementManager')->get(ElementModulateurCentreCoutForm::class);
    }
}