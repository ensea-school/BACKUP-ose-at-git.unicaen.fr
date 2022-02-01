<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ModificationServiceDuFieldset;

/**
 * Description of ModificationServiceDuFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuFieldsetAwareInterface
{
    /**
     * @param ModificationServiceDuFieldset|null $formIntervenantModificationServiceDuFieldset
     *
     * @return self
     */
    public function setFormIntervenantModificationServiceDuFieldset( ModificationServiceDuFieldset $formIntervenantModificationServiceDuFieldset );



    public function getFormIntervenantModificationServiceDuFieldset(): ?ModificationServiceDuFieldset;
}