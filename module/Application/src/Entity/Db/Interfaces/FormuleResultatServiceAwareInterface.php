<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleResultatService;

/**
 * Description of FormuleResultatServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatServiceAwareInterface
{
    /**
     * @param FormuleResultatService $formuleResultatService
     * @return self
     */
    public function setFormuleResultatService( FormuleResultatService $formuleResultatService = null );



    /**
     * @return FormuleResultatService
     */
    public function getFormuleResultatService();
}