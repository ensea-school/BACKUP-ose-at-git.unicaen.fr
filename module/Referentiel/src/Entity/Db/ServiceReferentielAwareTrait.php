<?php

namespace Application\Entity\Db\Traits;

use Referentiel\Entity\Db\ServiceReferentiel;

/**
 * Description of ServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielAwareTrait
{
    protected ?ServiceReferentiel $serviceReferentiel = null;



    /**
     * @param ServiceReferentiel $serviceReferentiel
     *
     * @return self
     */
    public function setServiceReferentiel(?ServiceReferentiel $serviceReferentiel)
    {
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }



    public function getServiceReferentiel(): ?ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }
}