<?php

namespace EtatSortie\Service;

/**
 * Description of EtatSortieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatSortieServiceAwareTrait
{
    protected ?EtatSortieService $serviceEtatSortie = null;



    /**
     * @param EtatSortieService $serviceEtatSortie
     *
     * @return self
     */
    public function setServiceEtatSortie(?EtatSortieService $serviceEtatSortie)
    {
        $this->serviceEtatSortie = $serviceEtatSortie;

        return $this;
    }



    public function getServiceEtatSortie(): ?EtatSortieService
    {
        if (empty($this->serviceEtatSortie)) {
            $this->serviceEtatSortie = \Unicaen\Framework\Application\Application::getInstance()->container()->get(EtatSortieService::class);
        }

        return $this->serviceEtatSortie;
    }
}