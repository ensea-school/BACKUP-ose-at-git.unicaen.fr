<?php

namespace Application\Service\Traits;

use Application\Service\UtilisateurService;

/**
 * Description of UtilisateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait UtilisateurServiceAwareTrait
{
    protected ?UtilisateurService $serviceUtilisateur;



    /**
     * @param UtilisateurService|null $serviceUtilisateur
     *
     * @return self
     */
    public function setServiceUtilisateur( ?UtilisateurService $serviceUtilisateur )
    {
        $this->serviceUtilisateur = $serviceUtilisateur;

        return $this;
    }



    public function getServiceUtilisateur(): ?UtilisateurService
    {
        if (!$this->serviceUtilisateur){
            $this->serviceUtilisateur = \Application::$container->get(UtilisateurService::class);
        }

        return $this->serviceUtilisateur;
    }
}