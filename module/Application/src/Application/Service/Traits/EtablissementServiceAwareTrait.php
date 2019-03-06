<?php

namespace Application\Service\Traits;

use Application\Service\EtablissementService;

/**
 * Description of EtablissementAwareTrait
 *
 * @author UnicaenCode
 */
trait EtablissementServiceAwareTrait
{
    /**
     * @var EtablissementService
     */
    private $serviceEtablissement;



    /**
     * @param EtablissementService $serviceEtablissement
     *
     * @return self
     */
    public function setServiceEtablissement(EtablissementService $serviceEtablissement)
    {
        $this->serviceEtablissement = $serviceEtablissement;

        return $this;
    }



    /**
     * @return EtablissementService
     */
    public function getServiceEtablissement()
    {
        if (empty($this->serviceEtablissement)) {
            $this->serviceEtablissement = \Application::$container->get(EtablissementService::class);
        }

        return $this->serviceEtablissement;
    }
}