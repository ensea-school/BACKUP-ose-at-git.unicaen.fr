<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleServiceModifie;

/**
 * Description of FormuleServiceModifieAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceModifieAwareInterface
{
    /**
     * @param FormuleServiceModifie $formuleServiceModifie
     * @return self
     */
    public function setFormuleServiceModifie( FormuleServiceModifie $formuleServiceModifie = null );



    /**
     * @return FormuleServiceModifie
     */
    public function getFormuleServiceModifie();
}