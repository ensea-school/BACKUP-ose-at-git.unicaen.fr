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
        return $this->serviceSignatureFlow;
    }
}