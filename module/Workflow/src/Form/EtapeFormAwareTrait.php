<?php

namespace Workflow\Form;


/**
 * Description of EtapeFormAwareTrait
 *
 * @author UnicaenCode
 */
trait EtapeFormAwareTrait
{
    protected ?EtapeForm $formEtape = null;



    /**
     * @param EtapeForm $formEtape
     *
     * @return self
     */
    public function setFormEtape(?EtapeForm $formEtape)
    {
        $this->formEtape = $formEtape;

        return $this;
    }



    public function getFormEtape(): ?EtapeForm
    {
        if (!empty($this->formEtape)) {
            return $this->formEtape;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(EtapeForm::class);
    }
}