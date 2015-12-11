<?php

namespace Application\Form\OffreFormation\Traits;

use Application\Form\OffreFormation\EtapeModulateursSaisie;
use Application\Module;
use RuntimeException;

/**
 * Description of EtapeModulateursSaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeModulateursSaisieAwareTrait
{
    /**
     * @var EtapeModulateursSaisie
     */
    private $formOffreFormationEtapeModulateursSaisie;





    /**
     * @param EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie
     * @return self
     */
    public function setFormOffreFormationEtapeModulateursSaisie( EtapeModulateursSaisie $formOffreFormationEtapeModulateursSaisie )
    {
        $this->formOffreFormationEtapeModulateursSaisie = $formOffreFormationEtapeModulateursSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return EtapeModulateursSaisie
     * @throws RuntimeException
     */
    public function getFormOffreFormationEtapeModulateursSaisie()
    {
        if (!empty($this->formOffreFormationEtapeModulateursSaisie)){
            return $this->formOffreFormationEtapeModulateursSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('EtapeModulateursSaisie');
    }
}