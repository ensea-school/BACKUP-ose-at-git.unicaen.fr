<?php

namespace Application\Form\Intervenant\Interfaces;

use Application\Form\Intervenant\HeuresCompForm;
use RuntimeException;

/**
 * Description of HeuresCompFormAwareInterface
 *
 * @author UnicaenCode
 */
interface HeuresCompFormAwareInterface
{
    /**
     * @param HeuresCompForm $formIntervenantHeuresComp
     * @return self
     */
    public function setFormIntervenantHeuresComp( HeuresCompForm $formIntervenantHeuresComp );



    /**
     * @return HeuresCompFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormIntervenantHeuresComp();
}