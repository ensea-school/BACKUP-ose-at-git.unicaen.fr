<?php

namespace Signature\Service;


/**
 * Description of SignatureFlowServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait SignatureFlowServiceAwareTrait
{
    protected ?SignatureFlowService $serviceSignatureFlow = null;



    /**
     * @param ?SignatureFlowService $serviceSignatureFlow
     *
     * @return self
     */
    public function setServiceSignatureFlow(?SignatureFlowService $serviceSignatureFlow): self
    {
        $this->serviceSignatureFlow = $serviceSignatureFlow;

        return $this;
    }



    public function getServiceSignatureFlow(): ?SignatureFlowService
    {
        if (empty($this->serviceSignatureFlow)) {
            $this->serviceSignatureFlow = \Framework\Application\Application::getInstance()->container()->get(SignatureFlowService::class);
        }

        return $this->serviceSignatureFlow;
    }
}