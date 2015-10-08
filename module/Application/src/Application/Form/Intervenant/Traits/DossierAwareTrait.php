<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\Dossier;
use Application\Module;
use RuntimeException;

/**
 * Description of DossierAwareTrait
 *
 * @author UnicaenCode
 */
trait DossierAwareTrait
{
    /**
     * @var Dossier
     */
    private $formIntervenantDossier;





    /**
     * @param Dossier $formIntervenantDossier
     * @return self
     */
    public function setFormIntervenantDossier( Dossier $formIntervenantDossier )
    {
        $this->formIntervenantDossier = $formIntervenantDossier;
        return $this;
    }



    /**
     * @return Dossier
     * @throws RuntimeException
     */
    public function getFormIntervenantDossier()
    {
        if (empty($this->formIntervenantDossier)){
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
            $this->formIntervenantDossier = $serviceLocator->getServiceLocator('FormElementManager')->get('IntervenantDossier');
        }
        return $this->formIntervenantDossier;
    }
}