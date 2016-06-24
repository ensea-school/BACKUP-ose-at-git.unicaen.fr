<?php

namespace Application\Service\Traits;

use Application\Service\TauxHoraireHETDService;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServiceTauxHoraireHETD( TauxHoraireHETDService $serviceTauxHoraireHETD )
    {
        $this->serviceTauxHoraireHETD = $serviceTauxHoraireHETD;
        return $this;
    }



    /**
     * @return TauxHoraireHETDService
     * @throws RuntimeException
     */
    public function getServiceTauxHoraireHETD()
    {
        if (empty($this->serviceTauxHoraireHETD)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator) {
                if (!method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            $this->serviceTauxHoraireHETD = $serviceLocator->get('applicationTauxHoraireHETD');
        }
        return $this->serviceTauxHoraireHETD;
    }
}