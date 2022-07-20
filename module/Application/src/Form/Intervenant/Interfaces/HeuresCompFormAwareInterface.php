<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\HeuresCompForm;

/**
 * Description of HeuresCompFormAwareInterface
 *
 * @author UnicaenCode
 */
interface HeuresCompFormAwareInterface
{
    /**
     * @param HeuresCompForm|null $formIntervenantHeuresComp
     *
     * @return self
     */
    public function setFormIntervenantHeuresComp( ?HeuresCompForm $formIntervenantHeuresComp );



    public function getFormIntervenantHeuresComp(): ?HeuresCompForm;
}