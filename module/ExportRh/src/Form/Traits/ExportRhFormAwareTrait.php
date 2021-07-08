<?php

namespace ExportRh\Form\Traits;


use ExportRh\Form\ExportRhForm;

trait ExportRhFormAwareTrait
{

    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ExportRhForm
     */
    public function getExportRhForm()
    {
        return \Application::$container->get('FormElementManager')->get(ExportRhForm::class, []);
    }
}