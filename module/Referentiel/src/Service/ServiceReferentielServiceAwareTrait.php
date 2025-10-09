<?php

namespace Referentiel\Service;

/**
 * Description of ServiceReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielServiceAwareTrait
{
    protected ?ServiceReferentielService $serviceServiceReferentiel = null;



    /**
     * @param ServiceReferentielService $serviceServiceReferentiel
     *
     * @return self
     */
    public function setServiceServiceReferentiel(?ServiceReferentielService $serviceServiceReferentiel)
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;

        return $this;
    }



    public function getServiceServiceReferentiel(): ?ServiceReferentielService
    {
        if (empty($this->serviceServiceReferentiel)) {
            $this->serviceServiceReferentiel = \Unicaen\Framework\Application\Application::getInstance()->container()->get(ServiceReferentielService::class);
        }

        return $this->serviceServiceReferentiel;
    }
}