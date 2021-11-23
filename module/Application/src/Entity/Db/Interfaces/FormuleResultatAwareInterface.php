<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleResultat;

/**
 * Description of FormuleResultatAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatAwareInterface
{
    /**
     * @param FormuleResultat $formuleResultat
     * @return self
     */
    public function setFormuleResultat( FormuleResultat $formuleResultat = null );



    /**
     * @return FormuleResultat
     */
    public function getFormuleResultat();
}