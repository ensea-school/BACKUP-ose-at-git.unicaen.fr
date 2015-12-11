<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\EtapeSaisie;
use Application\Module;
use RuntimeException;

/**
 * Description of EtapeSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeSaisieAwareTrait
{
    /**
     * @var EtapeSaisie
     */
    private $formOffreFormationEtapeSaisie;





    /**
     * @param EtapeSaisie $formOffreFormationEtapeSaisie
     * @return self
     */
    public function setFormOffreFormationEtapeSaisie( EtapeSaisie $formOffreFormationEtapeSaisie )
    {
        $this->formOffreFormationEtapeSaisie = $formOffreFormationEtapeSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeSaisie
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeSaisie()
    {
        if (!empty($this->formOffreFormationEtapeSaisie)){
            return $this->formOffreFormationEtapeSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('EtapeSaisie');
    }
}