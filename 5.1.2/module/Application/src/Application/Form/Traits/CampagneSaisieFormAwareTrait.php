<?php

namespace Application\Form\Traits;

use Application\Form\CampagneSaisieForm;
use Application\Module;
use RuntimeException;

/**
 * Description of CampagneSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieFormAwareTrait
{
    /**
     * @var CampagneSaisieForm
     */
    private $formCampagneSaisie;





    /**
     * @param CampagneSaisieForm $formCampagneSaisie
     * @return self
     */
    public function setFormCampagneSaisie( CampagneSaisieForm $formCampagneSaisie )
    {
        $this->formCampagneSaisie = $formCampagneSaisie;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return CampagneSaisieForm
     * @throws RuntimeException
     */
    public function getFormCampagneSaisie()
    {
        if (!empty($this->formCampagneSaisie)){
            return $this->formCampagneSaisie;
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
        return $serviceLocator->get('FormElementManager')->get('CampagneSaisie');
    }
}