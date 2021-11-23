<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ServiceReferentiel;

/**
 * Description of ServiceReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait ServiceReferentielAwareTrait
{
    /**
     * @var ServiceReferentiel
     */
    private $serviceReferentiel;





    /**
     * @param ServiceReferentiel $serviceReferentiel
     * @return self
     */
    public function setServiceReferentiel( ServiceReferentiel $serviceReferentiel = null )
    {
        $this->serviceReferentiel = $serviceReferentiel;
        return $this;
    }



    /**
     * @return ServiceReferentiel
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }
}