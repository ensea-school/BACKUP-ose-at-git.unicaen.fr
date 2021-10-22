<?php

namespace Plafond\Form;


/**
 * Description of PlafondConfigFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondConfigFormAwareTrait
{
    /**
     * @var PlafondConfigForm
     */
    protected $formPlafondConfig;



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



    /**
     * @return PlafondConfigForm
     */
    public function getFormPlafondConfig(): ?PlafondConfigForm
    {
        if (!$this->formPlafondConfig){
            $this->formPlafondConfig = \Application::$container->get('FormElementManager')->get(PlafondConfigForm::class);
        }

        return $this->formPlafondConfig;
    }
}