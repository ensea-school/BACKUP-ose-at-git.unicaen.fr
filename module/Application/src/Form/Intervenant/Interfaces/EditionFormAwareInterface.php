<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\EditionForm;

/**
 * Description of EditionFormAwareInterface
 *
 * @author UnicaenCode
 */
interface EditionFormAwareInterface
{
    /**
     * @param EditionForm|null $formIntervenantEdition
     *
     * @return self
     */
    public function setFormIntervenantEdition( ?EditionForm $formIntervenantEdition );



    public function getFormIntervenantEdition(): ?EditionForm;
}