<?php

namespace Application\Form\ServiceReferentiel\Traits;

use Application\Form\ServiceReferentiel\SaisieFieldset;
use Application\Module;
use RuntimeException;

/**
 * Description of SaisieFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieFieldsetAwareTrait
{
    /**
     * @var SaisieFieldset
     */
    private $fieldsetServiceReferentielSaisie;





    /**
     * @param SaisieFieldset $fieldsetServiceReferentielSaisie
     * @return self
     */
    public function setFieldsetServiceReferentielSaisie( SaisieFieldset $fieldsetServiceReferentielSaisie )
    {
        $this->fieldsetServiceReferentielSaisie = $fieldsetServiceReferentielSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieFieldset
     * @throws RuntimeException
     */
    public function getFieldsetServiceReferentielSaisie()
    {
        if (!empty($this->fieldsetServiceReferentielSaisie)){
            return $this->fieldsetServiceReferentielSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('ServiceReferentielSaisieFieldset');
    }
}