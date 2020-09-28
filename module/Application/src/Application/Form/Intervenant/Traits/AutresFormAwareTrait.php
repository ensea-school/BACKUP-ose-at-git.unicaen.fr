<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\AutresForm;

/**
 * Description of AutresFormAwareTrait
 *
 * @author UnicaenCode
 */
trait AutresFormAwareTrait
{
    /**
     * @var AutresFormAwareTrait
     */
    private $autresForm;



    /**
     * @return AutresForm
     */
    public function getAutresForm()
    {
        if (!empty($this->autresForm)) {
            return $this->autresForm;
        }

        return \Application::$container->get('FormElementManager')->get(AutresForm::class);
    }
}