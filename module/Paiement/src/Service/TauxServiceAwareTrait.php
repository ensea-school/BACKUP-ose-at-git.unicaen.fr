<?php

namespace Paiement\Service;


/**
 * Description of TauxServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TauxServiceAwareTrait
{
    protected ?TauxService $serviceTaux = null;



    /**
     * @param TauxService $serviceTaux
     *
     * @return self
     */
    public function setServiceTaux(?TauxService $serviceTaux)
    {
        $this->serviceTaux = $serviceTaux;

        return $this;
    }



    public function getServiceTaux(): ?TauxService
    {
        if (empty($this->serviceTaux)) {
            $this->serviceTaux = \Application::$container->get(TauxService::class);
        }

        return $this->serviceTaux;
    }
}

