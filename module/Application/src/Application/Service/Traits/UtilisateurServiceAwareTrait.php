<?php

namespace Application\Service\Traits;

use Application\Service\UtilisateurService;

/**
 * Description of UtilisateurAwareTrait
 *
 * @author UnicaenCode
 */
trait UtilisateurServiceAwareTrait
{
    /**
     * @var UtilisateurService
     */
    private $serviceUtilisateur;



    /**
     * @param UtilisateurService $serviceUtilisateur
     *
     * @return self
     */
    public function setServiceUtilisateur(UtilisateurService $serviceUtilisateur)
    {
        $this->serviceUtilisateur = $serviceUtilisateur;

        return $this;
    }



    /**
     * @return UtilisateurService
     */
    public function getServiceUtilisateur()
    {
        if (empty($this->serviceUtilisateur)) {
            $this->serviceUtilisateur = \Application::$container->get(UtilisateurService::class);
        }

        return $this->serviceUtilisateur;
    }
}