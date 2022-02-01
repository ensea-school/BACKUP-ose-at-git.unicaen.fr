<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Traits;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;

/**
 * Description of EtapeCentreCoutFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeCentreCoutFormAwareTrait
{
    protected ?EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout = null;



    /**
     * @param EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout( ?EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout )
    {
        $this->formOffreFormationEtapeCentreCoutEtapeCentreCout = $formOffreFormationEtapeCentreCoutEtapeCentreCout;

        return $this;
    }



    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout(): ?EtapeCentreCoutForm
    {
        if (empty($this->formOffreFormationEtapeCentreCoutEtapeCentreCout)){
            $this->formOffreFormationEtapeCentreCoutEtapeCentreCout = \Application::$container->get('FormElementManager')->get(EtapeCentreCoutForm::class);
        }

        return $this->formOffreFormationEtapeCentreCoutEtapeCentreCout;
    }
}