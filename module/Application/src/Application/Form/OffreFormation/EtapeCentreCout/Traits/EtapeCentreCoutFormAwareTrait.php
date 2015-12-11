<?php

namespace Application\Form\OffreFormation\EtapeCentreCout\Traits;

use Application\Form\OffreFormation\EtapeCentreCout\EtapeCentreCoutForm;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setFormOffreFormationEtapeCentreCoutEtapeCentreCout( EtapeCentreCoutForm $formOffreFormationEtapeCentreCoutEtapeCentreCout )
    {
        $this->formOffreFormationEtapeCentreCoutEtapeCentreCout = $formOffreFormationEtapeCentreCoutEtapeCentreCout;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeCentreCoutForm
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeCentreCoutEtapeCentreCout()
    {
        if (!empty($this->formOffreFormationEtapeCentreCoutEtapeCentreCout)){
            return $this->formOffreFormationEtapeCentreCoutEtapeCentreCout;
        }

        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        return $serviceLocator->get('FormElementManager')->get('EtapeCentreCoutForm');
    }
}