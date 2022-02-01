<?php

namespace ExportRh\Form\Traits;

use ExportRh\Form\ExportRhForm;

/**
 * Description of ExportRhFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ExportRhFormAwareTrait
{
    protected ?ExportRhForm $formExportRh;



    /**
     * @param ExportRhForm|null $formExportRh
     *
     * @return self
     */
    public function setFormExportRh( ?ExportRhForm $formExportRh )
    {
        $this->formExportRh = $formExportRh;

        return $this;
    }



    public function getFormExportRh(): ?ExportRhForm
    {
        if (!$this->formExportRh){
            $this->formExportRh = \Application::$container->get('FormElementManager')->get(ExportRhForm::class);
        }

        return $this->formExportRh;
    }
}