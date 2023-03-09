<?php

namespace Contrat\Form;


/**
 * Description of ContratRetourAwareInterface
 *
 * @author UnicaenCode
 */
interface ContratRetourFormAwareInterface
{
    /**
     * @param ContratRetourForm|null $formIntervenantContratRetourForm
     *
     * @return self
     */
    public function setFormIntervenantContratRetourForm( ?ContratRetourForm $formIntervenantContratRetourForm );



    public function getFormIntervenantContratRetourForm(): ?ContratRetourForm;
}