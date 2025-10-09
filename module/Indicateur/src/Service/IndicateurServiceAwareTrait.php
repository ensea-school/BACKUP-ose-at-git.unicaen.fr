<?php

namespace Indicateur\Service;


/**
 * Description of IndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurServiceAwareTrait
{
    protected ?IndicateurService $serviceIndicateur = null;



    /**
     * @param IndicateurService $serviceIndicateur
     *
     * @return self
     */
    public function setServiceIndicateur(?IndicateurService $serviceIndicateur)
    {
        $this->serviceIndicateur = $serviceIndicateur;

        return $this;
    }



    public function getServiceIndicateur(): ?IndicateurService
    {
        if (empty($this->serviceIndicateur)) {
            $this->serviceIndicateur = \Unicaen\Framework\Application\Application::getInstance()->container()->get(IndicateurService::class);
        }

        return $this->serviceIndicateur;
    }
}