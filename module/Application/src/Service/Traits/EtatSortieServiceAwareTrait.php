<?php

namespace Application\Service\Traits;

use Application\Service\EtatSortieService;

/**
 * Description of EtatSortieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatSortieServiceAwareTrait
{
    /**
     * @var EtatSortieService
     */
    protected $serviceEtatSortie;



    /**
     * @param EtatSortieService $serviceEtatSortie
     * @return self
     */
    public function setServiceEtatSortie( EtatSortieService $serviceEtatSortie )
    {
        $this->serviceEtatSortie = $serviceEtatSortie;

        return $this;
    }



    /**
     * @return EtatSortieService
     */
    public function getServiceEtatSortie() : EtatSortieService
    {
        if (!$this->serviceEtatSortie){
            $this->serviceEtatSortie = \Application::$container->get(EtatSortieService::class);
        }

        return $this->serviceEtatSortie;
    }
}