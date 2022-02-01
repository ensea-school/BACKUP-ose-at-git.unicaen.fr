<?php

namespace Application\Service\Traits;

use Application\Service\TauxHoraireHETDService;

/**
 * Description of TauxHoraireHETDServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxHoraireHETDServiceAwareTrait
{
    protected ?TauxHoraireHETDService $serviceTauxHoraireHETD;



    /**
     * @param TauxHoraireHETDService|null $serviceTauxHoraireHETD
     *
     * @return self
     */
    public function setServiceTauxHoraireHETD( ?TauxHoraireHETDService $serviceTauxHoraireHETD )
    {
        $this->serviceTauxHoraireHETD = $serviceTauxHoraireHETD;

        return $this;
    }



    public function getServiceTauxHoraireHETD(): ?TauxHoraireHETDService
    {
        if (!$this->serviceTauxHoraireHETD){
            $this->serviceTauxHoraireHETD = \Application::$container->get(TauxHoraireHETDService::class);
        }

        return $this->serviceTauxHoraireHETD;
    }
}