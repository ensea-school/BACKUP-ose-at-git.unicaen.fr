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
    /**
     * @var TauxHoraireHETDService
     */
    private $serviceTauxHoraireHETD;



    /**
     * @param TauxHoraireHETDService $serviceTauxHoraireHETD
     *
     * @return self
     */
    public function setServiceTauxHoraireHETD(TauxHoraireHETDService $serviceTauxHoraireHETD)
    {
        $this->serviceTauxHoraireHETD = $serviceTauxHoraireHETD;

        return $this;
    }



    /**
     * @return TauxHoraireHETDService
     */
    public function getServiceTauxHoraireHETD()
    {
        if (empty($this->serviceTauxHoraireHETD)) {
            $this->serviceTauxHoraireHETD = \Application::$container->get('applicationTauxHoraireHETD');
        }

        return $this->serviceTauxHoraireHETD;
    }
}