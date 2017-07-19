<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleService;

/**
 * Description of FormuleServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleServiceAwareInterface
{
    /**
     * @param FormuleService $formuleService
     * @return self
     */
    public function setFormuleService( FormuleService $formuleService = null );



    /**
     * @return FormuleService
     */
    public function getFormuleService();
}