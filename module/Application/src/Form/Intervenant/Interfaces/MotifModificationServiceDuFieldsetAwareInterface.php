<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\MotifModificationServiceDuFieldset;

/**
 * Description of MotifModificationServiceDuFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifModificationServiceDuFieldsetAwareInterface
{
    /**
     * @param MotifModificationServiceDuFieldset|null $formIntervenantMotifModificationServiceDuFieldset
     *
     * @return self
     */
    public function setFormIntervenantMotifModificationServiceDuFieldset( ?MotifModificationServiceDuFieldset $formIntervenantMotifModificationServiceDuFieldset );



    public function getFormIntervenantMotifModificationServiceDuFieldset(): ?MotifModificationServiceDuFieldset;
}