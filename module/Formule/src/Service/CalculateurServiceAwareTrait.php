<?php

namespace Formule\Service;


/**
 * Description of CalculateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CalculateurServiceAwareTrait
{
    protected ?CalculateurService $serviceCalculateur = null;



    /**
     * @param CalculateurService $serviceCalculateur
     *
     * @return self
     */
    public function setServiceCalculateur(?CalculateurService $serviceCalculateur)
    {
        $this->serviceCalculateur = $serviceCalculateur;

        return $this;
    }



    public function getServiceCalculateur(): ?CalculateurService
    {
        if (empty($this->serviceCalculateur)) {
            $this->serviceCalculateur = \OseAdmin::instance()->container()->get(CalculateurService::class);
        }

        return $this->serviceCalculateur;
    }
}