<?php

namespace Application\Service\Traits;

use Application\Service\EtablissementService;

/**
 * Description of EtablissementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementServiceAwareTrait
{
    protected ?EtablissementService $serviceEtablissement;



    /**
     * @param EtablissementService|null $serviceEtablissement
     *
     * @return self
     */
    public function setServiceEtablissement( ?EtablissementService $serviceEtablissement )
    {
        $this->serviceEtablissement = $serviceEtablissement;

        return $this;
    }



    public function getServiceEtablissement(): ?EtablissementService
    {
        if (!$this->serviceEtablissement){
            $this->serviceEtablissement = \Application::$container->get(EtablissementService::class);
        }

        return $this->serviceEtablissement;
    }
}