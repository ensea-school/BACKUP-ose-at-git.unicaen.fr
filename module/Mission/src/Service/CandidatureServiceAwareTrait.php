<?php

namespace Mission\Service;


use Mission\Entity\Db\OffreEmploi;

/**
 * Description of OffreEmploiServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CandidatureServiceAwareTrait
{
    protected ?CandidatureService $serviceCandidature = null;



    /**
     * @param CandidatureService $serviceCandidature
     *
     * @return self
     */
    public function setServiceCandidature(?CandidatureService $serviceCandidature)
    {
        $this->serviceCandidature = $serviceCandidature;

        return $this;
    }



    public function getServiceCandidature(): ?CandidatureService
    {
        if (empty($this->serviceCandidature)) {
            $this->serviceCandidature = \Framework\Application\Application::getInstance()->container()->get(CandidatureService::class);
        }

        return $this->serviceCandidature;
    }
}