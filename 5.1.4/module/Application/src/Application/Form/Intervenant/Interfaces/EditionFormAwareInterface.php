<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\EditionForm;
use RuntimeException;

/**
 * Description of EditionFormAwareInterface
 *
 * @author UnicaenCode
 */
interface EditionFormAwareInterface
{
    /**
     * @param EditionForm $formIntervenantEdition
     * @return self
     */
    public function setFormIntervenantEdition( EditionForm $formIntervenantEdition );



    /**
     * @return EditionFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantEdition();
}