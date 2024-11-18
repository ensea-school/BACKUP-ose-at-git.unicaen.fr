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
    public function setServiceSignatureFlowStep(?SignatureFlowStepService $serviceSignatureFlowStep): self
    {
        $this->serviceSignatureFlowStep = $serviceSignatureFlowStep;

        return $this;
    }



    public function getServiceSignatureFlowStep(): ?SignatureFlowStepService
    {
        return $this->serviceSignatureFlowStep;
    }
}