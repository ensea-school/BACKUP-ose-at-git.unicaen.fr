<?php

namespace Application\Service\Traits;

use Application\Service\CampagneSaisieService;
use Application\Module;
use RuntimeException;

/**
 * Description of CampagneSaisieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieServiceAwareTrait
{
    /**
     * @var CampagneSaisieService
     */
    private $serviceCampagneSaisie;





    /**
     * @param CampagneSaisieService $serviceCampagneSaisie
     * @return self
     */
    public function setServiceCampagneSaisie( CampagneSaisieService $serviceCampagneSaisie )
    {
        $this->serviceCampagneSaisie = $serviceCampagneSaisie;
        return $this;
    }



    /**
     * @return CampagneSaisieService
     * @throws RuntimeException
     */
    public function getServiceCampagneSaisie()
    {
        if (empty($this->serviceCampagneSaisie)){
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
            $this->serviceCampagneSaisie = $serviceLocator->get('applicationCampagneSaisie');
        }
        return $this->serviceCampagneSaisie;
    }
}