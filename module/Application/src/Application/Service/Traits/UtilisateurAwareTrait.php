<?php

namespace Application\Service\Traits;

use Application\Service\Utilisateur;

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
     *
     * @return self
     */
    public function setServiceUtilisateur(Utilisateur $serviceUtilisateur)
    {
        $this->serviceUtilisateur = $serviceUtilisateur;

        return $this;
    }



    /**
     * @return Utilisateur
     */
    public function getServiceUtilisateur()
    {
        if (empty($this->serviceUtilisateur)) {
            $this->serviceUtilisateur = \Application::$container->get('ApplicationUtilisateur');
        }

        return $this->serviceUtilisateur;
    }
}