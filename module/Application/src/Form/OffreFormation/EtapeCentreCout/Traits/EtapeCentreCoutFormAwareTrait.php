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
    /**
     * @var EtapeCentreCoutForm
     */
    private $formOffreFormationEtapeCentreCoutEtapeCentreCout;



    /**
     * @param EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout
     *
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout(EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout)
    {
        $this->formOffreFormationEtapeCentreCoutEtapeCentreCout = $formOffreFormationEtapeCentreCoutEtapeCentreCout;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeCentreCoutForm
     */
    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout()
    {
        if (!empty($this->formOffreFormationEtapeCentreCoutEtapeCentreCout)) {
            return $this->formOffreFormationEtapeCentreCoutEtapeCentreCout;
        }

        return \Application::$container->get('FormElementManager')->get(EtapeCentreCoutForm::class);
    }
}