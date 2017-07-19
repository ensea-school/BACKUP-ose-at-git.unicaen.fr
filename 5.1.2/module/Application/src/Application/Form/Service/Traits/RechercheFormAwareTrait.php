<?php

namespace Application\Form\Service\Traits;

use Application\Form\Service\RechercheForm;
use Application\Module;
use RuntimeException;

/**
 * Description of RechercheFormAwareTrait
 *
 * @author UnicaenCode
 */
trait RechercheFormAwareTrait
{
    /**
     * @var RechercheForm
     */
    private $formServiceRecherche;





    /**
     * @param RechercheForm $formServiceRecherche
     * @return self
     */
    public function setFormServiceRecherche( RechercheForm $formServiceRecherche )
    {
        $this->formServiceRecherche = $formServiceRecherche;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return RechercheForm
     * @throws RuntimeException
     */
    public function getFormServiceRecherche()
    {
        if (!empty($this->formServiceRecherche)){
            return $this->formServiceRecherche;
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
        return $serviceLocator->get('FormElementManager')->get('ServiceRechercheForm');
    }
}