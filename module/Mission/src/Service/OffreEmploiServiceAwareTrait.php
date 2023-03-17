<?php

namespace Mission\Service;


use Mission\Entity\Db\OffreEmploi;

/**
 * Description of OffreEmploiServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait OffreEmploiServiceAwareTrait
{
    protected ?OffreEmploiService $serviceMission = null;



    /**
     * @param OffreEmploiService $serviceOffreEmploi
     *
     * @return self
     */
    public function setServiceOffreEmploi(?OffreEmploiService $serviceOffreEmploi)
    {
        $this->serviceOffreEmploi = $serviceOffreEmploi;

        return $this;
    }



    public function getServiceOffreEmploi(): ?OffreEmploiService
    {
        if (empty($this->serviceOffreEmploi)) {
            $this->serviceOffreEmploi = \Application::$container->get(OffreEmploiService::class);
        }

        return $this->serviceOffreEmploi;
    }
}