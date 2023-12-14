<?php

namespace Paiement\Service;


/**
 * Description of TauxServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxRemuServiceAwareTrait
{
    protected ?TauxRemuService $serviceTaux = null;



    /**
     * @param TauxRemuService|null $serviceTaux
     *
     * @return self
     */
    public function setServiceTauxRemu(?TauxRemuService $serviceTaux)
    {
        $this->serviceTaux = $serviceTaux;

        return $this;
    }



    public function getServiceTauxRemu(): ?TauxRemuService
    {
        if (empty($this->serviceTaux)) {
            $this->serviceTaux = \OseAdmin::instance()->container()->get(TauxRemuService::class);
        }

        return $this->serviceTaux;
    }
}

