<?php

namespace Workflow\Service;

trait TransfertParametrageServiceAwareTrait
{
    protected ?TransfertParametrageService $serviceTransfertParametrage = null;



    public function setServiceTransfertParametrage(?TransfertParametrageService $serviceTransfertParametrage): self
    {
        $this->serviceTransfertParametrage = $serviceTransfertParametrage;

        return $this;
    }



    public function getServiceTransfertParametrage(): ?TransfertParametrageService
    {
        if (empty($this->serviceTransfertParametrage)) {
            $this->serviceTransfertParametrage = \Unicaen\Framework\Application\Application::getInstance()->container()->get(TransfertParametrageService::class);
        }

        return $this->serviceTransfertParametrage;
    }
}
