<?php

namespace Application\Form\ServiceReferentiel\Traits;

use Application\Form\ServiceReferentiel\Saisie;
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
    private $formServiceReferentielSaisie;





    /**
     * @param Saisie $formServiceReferentielSaisie
     * @return self
     */
    public function setFormServiceReferentielSaisie( Saisie $formServiceReferentielSaisie )
    {
        $this->formServiceReferentielSaisie = $formServiceReferentielSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     * @throws RuntimeException
     */
    public function getFormServiceReferentielSaisie()
    {
        if (!empty($this->formServiceReferentielSaisie)){
            return $this->formServiceReferentielSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('ServiceReferentielSaisie');
    }
}