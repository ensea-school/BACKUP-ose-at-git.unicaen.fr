<?php

namespace Application\Service\Traits;

use Application\Service\Utilisateur;
use Common\Exception\RuntimeException;

trait UtilisateurAwareTrait
{
    /**
     * description
     *
     * @var Utilisateur
     */
    private $serviceUtilisateur;

    /**
     *
     * @param Utilisateur $serviceUtilisateur
     * @return self
     */
    public function setServiceUtilisateur( Utilisateur $serviceUtilisateur )
    {
        $this->serviceUtilisateur = $serviceUtilisateur;
        return $this;
    }

    /**
     *
     * @return Utilisateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceUtilisateur()
    {
        if (empty($this->serviceUtilisateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationUtilisateur');
        }else{
            return $this->serviceUtilisateur;
        }
    }

}