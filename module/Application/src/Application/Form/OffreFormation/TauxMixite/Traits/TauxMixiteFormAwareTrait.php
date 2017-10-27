<?php

namespace Application\Form\OffreFormation\TauxMixite\Traits;

use Application\Form\OffreFormation\TauxMixite\TauxMixiteForm;
use Application\Module;
use RuntimeException;

/**
 * Description of TauxMixiteFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxMixiteFormAwareTrait
{
    /**
     * @var TauxMixiteForm
     */
    private $formOffreFormationTauxMixite;





    /**
     * @param TauxMixiteForm $formOffreFormationTauxMixite
     * @return self
     */
    public function setFormOffreFormationTauxMixite( TauxMixiteForm $formOffreFormationTauxMixite )
    {
        $this->formOffreFormationTauxMixite = $formOffreFormationTauxMixite;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TauxMixiteForm
     * @throws RuntimeException
     */
    public function getFormOffreFormationTauxMixite()
    {
        if (!empty($this->formOffreFormationTauxMixite)){
            return $this->formOffreFormationTauxMixite;
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
        return $serviceLocator->get('FormElementManager')->get(TauxMixiteForm::class);
    }
}