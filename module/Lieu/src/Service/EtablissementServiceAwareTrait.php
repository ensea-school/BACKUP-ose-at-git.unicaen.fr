<?php

namespace Lieu\Service;

/**
 * Description of EtablissementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementServiceAwareTrait
{
    protected ?EtablissementService $serviceEtablissement = null;



    /**
     * @param EtablissementService $serviceEtablissement
     *
     * @return self
     */
    public function setServiceEtablissement(?EtablissementService $serviceEtablissement)
    {
        $this->serviceEtablissement = $serviceEtablissement;

        return $this;
    }



    public function getServiceEtablissement(): ?EtablissementService
    {
        if (empty($this->serviceEtablissement)) {
            $this->serviceEtablissement = \OseAdmin::instance()->container()->get(EtablissementService::class);
        }

        return $this->serviceEtablissement;
    }
}