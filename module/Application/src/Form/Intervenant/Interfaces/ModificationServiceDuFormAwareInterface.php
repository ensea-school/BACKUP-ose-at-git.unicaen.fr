<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ModificationServiceDuForm;
use RuntimeException;

/**
 * Description of ModificationServiceDuFormAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuFormAwareInterface
{
    /**
     * @param ModificationServiceDuForm $formIntervenantModificationServiceDu
     * @return self
     */
    public function setFormIntervenantModificationServiceDu( ModificationServiceDuForm $formIntervenantModificationServiceDu );



    /**
     * @return ModificationServiceDuFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantModificationServiceDu();
}