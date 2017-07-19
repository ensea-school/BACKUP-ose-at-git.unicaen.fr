<?php

namespace Application\Service\Traits;

use Application\Service\Utilisateur;
use Application\Module;
use RuntimeException;

/**
 * Description of UtilisateurAwareTrait
 *
 * @author UnicaenCode
 */
trait UtilisateurAwareTrait
{
    /**
     * @var Utilisateur
     */
    private $serviceUtilisateur;





    /**
     * @param Utilisateur $serviceUtilisateur
     * @return self
     */
    public function setServiceUtilisateur( Utilisateur $serviceUtilisateur )
    {
        $this->serviceUtilisateur = $serviceUtilisateur;
        return $this;
    }



    /**
     * @return Utilisateur
     * @throws RuntimeException
     */
    public function getServiceUtilisateur()
    {
        if (empty($this->serviceUtilisateur)){
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
        $this->serviceUtilisateur = $serviceLocator->get('ApplicationUtilisateur');
        }
        return $this->serviceUtilisateur;
    }
}