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
     * @param TauxRemuService $serviceTaux
     *
     * @return self
     */
    public function setServiceTaux(?TauxRemuService $serviceTaux)
    {
        $this->serviceTaux = $serviceTaux;

        return $this;
    }



    public function getServiceTaux(): ?TauxRemuService
    {
        if (empty($this->serviceTaux)) {
            $this->serviceTaux = \Application::$container->get(TauxRemuService::class);
        }

        return $this->serviceTaux;
    }
}

