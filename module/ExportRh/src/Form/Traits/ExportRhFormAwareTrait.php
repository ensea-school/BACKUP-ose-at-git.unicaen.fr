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
    protected ?ExportRhForm $formExportRh = null;



    /**
     * @param ExportRhForm $formExportRh
     *
     * @return self
     */
    public function setFormExportRh(?ExportRhForm $formExportRh)
    {
        $this->formExportRh = $formExportRh;

        return $this;
    }



    public function getFormExportRh(): ?ExportRhForm
    {
        if (!empty($this->formExportRh)) {
            return $this->formExportRh;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(ExportRhForm::class);
    }
}