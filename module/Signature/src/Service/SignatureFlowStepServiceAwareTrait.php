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
        if (empty($this->serviceSignatureFlowStep)) {
            $this->serviceSignatureFlowStep = \AppAdmin::container()->get(SignatureFlowStepService::class);
        }

        return $this->serviceSignatureFlowStep;
    }
}