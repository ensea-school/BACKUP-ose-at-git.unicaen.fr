<?php

namespace Signature\Form;


/**
 * Description of SignatureFlowFormAwareTrait
 *
 */
trait SignatureFlowFormAwareTrait
{
    protected ?SignatureFlowForm $formSignatureFlow = null;



    /**
     * @param SignatureFlowForm $formSignatureFlow
     *
     * @return self
     */
    public function setFormSignatureFLow(?SignatureFlowForm $formSignatureFlow)
    {
        $this->formSignatureFlow = $formSignatureFlow;

        return $this;
    }



    public function getFormSignatureFLow(): ?SignatureFlowForm
    {
        if (empty($this->formSignatureFlow)) {
            return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(SignatureFlowForm::class);
        }
        return $this->formSignatureFlow;

    }
}