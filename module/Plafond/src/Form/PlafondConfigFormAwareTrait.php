<?php

namespace Plafond\Form;


/**
 * Description of PlafondConfigFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondConfigFormAwareTrait
{
    protected ?PlafondConfigForm $formPlafondConfig = null;



    /**
     * @param PlafondConfigForm $formPlafondConfig
     *
     * @return self
     */
    public function setFormPlafondConfig(?PlafondConfigForm $formPlafondConfig)
    {
        $this->formPlafondConfig = $formPlafondConfig;

        return $this;
    }



    public function getFormPlafondConfig(): ?PlafondConfigForm
    {
        if (!empty($this->formPlafondConfig)) {
            return $this->formPlafondConfig;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(PlafondConfigForm::class);
    }
}