<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleIntervenant;

/**
 * Description of FormuleIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleIntervenantAwareInterface
{
    /**
     * @param FormuleIntervenant $formuleIntervenant
     * @return self
     */
    public function setFormuleIntervenant( FormuleIntervenant $formuleIntervenant = null );



    /**
     * @return FormuleIntervenant
     */
    public function getFormuleIntervenant();
}