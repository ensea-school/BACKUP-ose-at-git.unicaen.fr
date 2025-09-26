<?php

namespace Utilisateur\Service;

/**
 * Description of UtilisateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait UtilisateurServiceAwareTrait
{
    protected ?UtilisateurService $serviceUtilisateur = null;



    /**
     * @param UtilisateurService $serviceUtilisateur
     *
     * @return self
     */
    public function setServiceUtilisateur(?UtilisateurService $serviceUtilisateur)
    {
        $this->serviceUtilisateur = $serviceUtilisateur;

        return $this;
    }



    public function getServiceUtilisateur(): ?UtilisateurService
    {
        if (empty($this->serviceUtilisateur)) {
            $this->serviceUtilisateur = \Framework\Application\Application::getInstance()->container()->get(UtilisateurService::class);
        }

        return $this->serviceUtilisateur;
    }
}