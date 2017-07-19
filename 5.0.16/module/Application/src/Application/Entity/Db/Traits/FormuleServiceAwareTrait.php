<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleService;

/**
 * Description of FormuleServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleServiceAwareTrait
{
    /**
     * @var FormuleService
     */
    private $formuleService;





    /**
     * @param FormuleService $formuleService
     * @return self
     */
    public function setFormuleService( FormuleService $formuleService = null )
    {
        $this->formuleService = $formuleService;
        return $this;
    }



    /**
     * @return FormuleService
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }
}