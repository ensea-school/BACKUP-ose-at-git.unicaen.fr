<?php

namespace Indicateur\Service;

/**
 * Description of IndicateurServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IndicateurServiceAwareTrait
{
    /**
     * @var IndicateurService
     */
    private $serviceIndicateur;



    /**
     * @param IndicateurService $serviceIndicateur
     *
     * @return self
     */
    public function setServiceIndicateur(IndicateurService $serviceIndicateur)
    {
        $this->serviceIndicateur = $serviceIndicateur;

        return $this;
    }



    /**
     * @return IndicateurService
     */
    public function getServiceIndicateur()
    {
        if (empty($this->serviceIndicateur)) {
            $this->serviceIndicateur = \Application::$container->get(IndicateurService::class);
        }

        return $this->serviceIndicateur;
    }
}