<?php

namespace Indicateur\Service;


/**
 * Description of IndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurServiceAwareTrait
{
    protected ?IndicateurService $serviceIndicateur;



    /**
     * @param IndicateurService|null $serviceIndicateur
     *
     * @return self
     */
    public function setServiceIndicateur( ?IndicateurService $serviceIndicateur )
    {
        $this->serviceIndicateur = $serviceIndicateur;

        return $this;
    }



    public function getServiceIndicateur(): ?IndicateurService
    {
        if (!$this->serviceIndicateur){
            $this->serviceIndicateur = \Application::$container->get(IndicateurService::class);
        }

        return $this->serviceIndicateur;
    }
}