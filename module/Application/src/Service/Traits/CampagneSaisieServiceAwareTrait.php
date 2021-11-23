<?php

namespace Application\Service\Traits;

use Application\Service\CampagneSaisieService;

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
     *
     * @return self
     */
    public function setServiceCampagneSaisie(CampagneSaisieService $serviceCampagneSaisie)
    {
        $this->serviceCampagneSaisie = $serviceCampagneSaisie;

        return $this;
    }



    /**
     * @return CampagneSaisieService
     */
    public function getServiceCampagneSaisie()
    {
        if (empty($this->serviceCampagneSaisie)) {
            $this->serviceCampagneSaisie = \Application::$container->get(CampagneSaisieService::class);
        }

        return $this->serviceCampagneSaisie;
    }
}