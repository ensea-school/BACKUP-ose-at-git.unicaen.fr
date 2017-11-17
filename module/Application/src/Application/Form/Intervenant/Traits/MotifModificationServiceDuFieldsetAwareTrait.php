<?php

namespace Application\Form\Intervenant\Traits;

use Application\Form\Intervenant\MotifModificationServiceDuFieldset;

/**
 * Description of MotifModificationServiceDuFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuFieldsetAwareTrait
{
    /**
     * @var MotifModificationServiceDuFieldset
     */
    private $fieldsetIntervenantMotifModificationServiceDu;



    /**
     * @param MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu
     *
     * @return self
     */
    public function setFieldsetIntervenantMotifModificationServiceDu(MotifModificationServiceDuFieldset $fieldsetIntervenantMotifModificationServiceDu)
    {
        $this->fieldsetIntervenantMotifModificationServiceDu = $fieldsetIntervenantMotifModificationServiceDu;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MotifModificationServiceDuFieldset
     */
    public function getFieldsetIntervenantMotifModificationServiceDu()
    {
        if (!empty($this->fieldsetIntervenantMotifModificationServiceDu)) {
            return $this->fieldsetIntervenantMotifModificationServiceDu;
        }

        return \Application::$container->get('FormElementManager')->get('IntervenantMotifModificationServiceDuFieldset');
    }
}