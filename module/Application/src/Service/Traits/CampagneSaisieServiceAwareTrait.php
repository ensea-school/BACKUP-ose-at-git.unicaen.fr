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
    protected ?CampagneSaisieService $serviceCampagneSaisie;



    /**
     * @param CampagneSaisieService|null $serviceCampagneSaisie
     *
     * @return self
     */
    public function setServiceCampagneSaisie( ?CampagneSaisieService $serviceCampagneSaisie )
    {
        $this->serviceCampagneSaisie = $serviceCampagneSaisie;

        return $this;
    }



    public function getServiceCampagneSaisie(): ?CampagneSaisieService
    {
        if (!$this->serviceCampagneSaisie){
            $this->serviceCampagneSaisie = \Application::$container->get(CampagneSaisieService::class);
        }

        return $this->serviceCampagneSaisie;
    }
}