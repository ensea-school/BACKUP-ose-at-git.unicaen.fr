<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ModificationServiceDuForm;

/**
 * Description of ModificationServiceDuFormAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuFormAwareInterface
{
    /**
     * @param ModificationServiceDuForm|null $formIntervenantModificationServiceDu
     *
     * @return self
     */
    public function setFormIntervenantModificationServiceDu( ?ModificationServiceDuForm $formIntervenantModificationServiceDu );



    public function getFormIntervenantModificationServiceDu(): ?ModificationServiceDuForm;
}