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
    public function setFormPlafondConfig( PlafondConfigForm $formPlafondConfig )
    {
        $this->formPlafondConfig = $formPlafondConfig;

        return $this;
    }



    public function getFormPlafondConfig(): ?PlafondConfigForm
    {
        if (empty($this->formPlafondConfig)){
            $this->formPlafondConfig = \Application::$container->get('FormElementManager')->get(PlafondConfigForm::class);
        }

        return $this->formPlafondConfig;
    }
}