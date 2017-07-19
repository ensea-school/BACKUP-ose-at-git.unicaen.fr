<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\Saisie;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    /**
     * @var Saisie
     */
    private $formVolumeHoraireSaisie;





    /**
     * @param Saisie $formVolumeHoraireSaisie
     * @return self
     */
    public function setFormVolumeHoraireSaisie( Saisie $formVolumeHoraireSaisie )
    {
        $this->formVolumeHoraireSaisie = $formVolumeHoraireSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormVolumeHoraireSaisie()
    {
        if (!empty($this->formVolumeHoraireSaisie)){
            return $this->formVolumeHoraireSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('VolumeHoraireSaisie');
    }
}