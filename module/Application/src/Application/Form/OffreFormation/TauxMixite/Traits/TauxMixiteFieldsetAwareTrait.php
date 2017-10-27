<?php

namespace Application\Form\OffreFormation\TauxMixite\Traits;

use Application\Form\OffreFormation\TauxMixite\TauxMixiteFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of TauxMixiteFieldsetAwareTrait
 *
 */
trait TauxMixiteFieldsetAwareTrait
{
    /**
     * @var TauxMixiteFieldset
     */
    private $fieldsetOffreFormationTauxMixite;





    /**
     * @param TauxMixiteFieldset $fieldsetOffreFormationTauxMixite
     * @return self
     */
    public function setFieldsetOffreFormationTauxMixite( TauxMixiteFieldset $fieldsetOffreFormationTauxMixite )
    {
        $this->fieldsetOffreFormationTauxMixite = $fieldsetOffreFormationTauxMixite;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TauxMixiteFieldset
     * @throws RuntimeException
     */
    public function getFieldsetOffreFormationTauxMixite()
    {
        if (!empty($this->fieldsetOffreFormationTauxMixite)){
            return $this->fieldsetOffreFormationTauxMixite;
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
        return $serviceLocator->get('FormElementManager')->get(TauxMixiteFieldset::class);
    }
}