<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\ContratValidation;
use RuntimeException;

/**
 * Description of ContratValidationAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratValidationAwareInterface
{
    /**
     * @param ContratValidation $formIntervenantContratValidation
     * @return self
     */
    public function setFormIntervenantContratValidation( ContratValidation $formIntervenantContratValidation );



    /**
     * @return ContratValidationAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantContratValidation();
}