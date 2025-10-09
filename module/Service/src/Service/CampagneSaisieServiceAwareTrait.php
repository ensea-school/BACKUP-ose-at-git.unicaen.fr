<?php

namespace Service\Service;

/**
 * Description of CampagneSaisieServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CampagneSaisieServiceAwareTrait
{
    protected ?CampagneSaisieService $serviceCampagneSaisie = null;



    /**
     * @param CampagneSaisieService $serviceCampagneSaisie
     *
     * @return self
     */
    public function setServiceCampagneSaisie(?CampagneSaisieService $serviceCampagneSaisie)
    {
        $this->serviceCampagneSaisie = $serviceCampagneSaisie;

        return $this;
    }



    public function getServiceCampagneSaisie(): ?CampagneSaisieService
    {
        if (empty($this->serviceCampagneSaisie)) {
            $this->serviceCampagneSaisie =\Unicaen\Framework\Application\Application::getInstance()->container()->get(CampagneSaisieService::class);
        }

        return $this->serviceCampagneSaisie;
    }
}