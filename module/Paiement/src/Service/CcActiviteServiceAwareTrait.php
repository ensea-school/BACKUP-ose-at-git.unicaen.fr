<?php

namespace Paiement\Service;


/**
 * Description of CcActiviteServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CcActiviteServiceAwareTrait
{
    protected ?CcActiviteService $serviceCcActivite = null;



    /**
     * @param CcActiviteService $serviceCcActivite
     *
     * @return self
     */
    public function setServiceCcActivite(?CcActiviteService $serviceCcActivite)
    {
        $this->serviceCcActivite = $serviceCcActivite;

        return $this;
    }



    public function getServiceCcActivite(): ?CcActiviteService
    {
        if (empty($this->serviceCcActivite)) {
            $this->serviceCcActivite = \Unicaen\Framework\Application\Application::getInstance()->container()->get(CcActiviteService::class);
        }

        return $this->serviceCcActivite;
    }
}