<?php

namespace Application\Form\Plafond\Traits;

use Application\Form\Plafond\PlafondApplicationForm;
use RuntimeException;

/**
 * Description of PlafondApplicationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondApplicationFormAwareTrait
{
    /**
     * @var PlafondApplicationForm
     */
    protected $formPlafondPlafondApplication;



    /**
     * @param PlafondApplicationForm $formPlafondPlafondApplication
     *
     * @return self
     */
    public function setFormPlafondPlafondApplication(PlafondApplicationForm $formPlafondPlafondApplication)
    {
        $this->formPlafondPlafondApplication = $formPlafondPlafondApplication;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return PlafondApplicationForm
     * @throws RuntimeException
     */
    public function getFormPlafondPlafondApplication()
    {
        if (!empty($this->formPlafondPlafondApplication)) {
            return $this->formPlafondPlafondApplication;
        }

        return \Application::$container->get('FormElementManager')->get(PlafondApplicationForm::class);
    }
}