<?php

namespace Signature\Service;


/**
 * Description of SignatureFlowStepServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait SignatureFlowStepServiceAwareTrait
{
    protected ?SignatureFlowStepService $serviceSignatureFlowStep = null;



    /**
     * @param ?SignatureFlowStepService $serviceSignatureFlowStep
     *
     * @return self
     */
    public function setserviceSignatureFlowStep(?SignatureFlowStepService $serviceSignatureFlowStep): self
    {
        $this->serviceSignatureFlowStep = $serviceSignatureFlowStep;

        return $this;
    }



    public function getserviceSignatureFlowStep(): ?SignatureFlowStepService
    {
        return $this->serviceSignatureFlowStep;
    }
}