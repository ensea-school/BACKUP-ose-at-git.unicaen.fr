<?php

namespace Signature\Form;


/**
 * Description of SignatureFlowStepFormAwareTrait
 *
 */
trait SignatureFlowStepFormAwareTrait
{
    protected ?SignatureFlowStepForm $formSignatureFlowStep = null;



    /**
     * @param SignatureFlowStepForm $formSignatureFlowStep
     *
     * @return self
     */
    public function setformSignatureFlowStep(?SignatureFlowStepForm $formSignatureFlowStep)
    {
        $this->formSignatureFlowStep = $formSignatureFlowStep;

        return $this;
    }



    public function getformSignatureFlowStep(): ?SignatureFlowStepForm
    {
        if (empty($this->formSignatureFlowStep)) {
            return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(SignatureFlowStepForm::class);
        }
        return $this->formSignatureFlowStep;
    }
}