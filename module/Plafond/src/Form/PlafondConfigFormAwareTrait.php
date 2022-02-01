<?php

namespace Plafond\Form;


/**
 * Description of PlafondConfigFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondConfigFormAwareTrait
{
    protected ?PlafondConfigForm $formPlafondConfig;



    /**
     * @param PlafondConfigForm|null $formPlafondConfig
     *
     * @return self
     */
    public function setFormPlafondConfig( ?PlafondConfigForm $formPlafondConfig )
    {
        $this->formPlafondConfig = $formPlafondConfig;

        return $this;
    }



    public function getFormPlafondConfig(): ?PlafondConfigForm
    {
        if (!$this->formPlafondConfig){
            $this->formPlafondConfig = \Application::$container->get('FormElementManager')->get(PlafondConfigForm::class);
        }

        return $this->formPlafondConfig;
    }
}