<?php

namespace Application\Form\VolumeHoraireReferentiel\Traits;

use Application\Form\VolumeHoraireReferentiel\Saisie;
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
    private $formVolumeHoraireReferentielSaisie;





    /**
     * @param Saisie $formVolumeHoraireReferentielSaisie
     * @return self
     */
    public function setFormVolumeHoraireReferentielSaisie( Saisie $formVolumeHoraireReferentielSaisie )
    {
        $this->formVolumeHoraireReferentielSaisie = $formVolumeHoraireReferentielSaisie;
        return $this;
    }



    /**
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormVolumeHoraireReferentielSaisie()
    {
        if (empty($this->formVolumeHoraireReferentielSaisie)){
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
            $this->formVolumeHoraireReferentielSaisie = $serviceLocator->getServiceLocator('FormElementManager')->get('VolumeHoraireReferentielSaisie');
        }
        return $this->formVolumeHoraireReferentielSaisie;
    }
}