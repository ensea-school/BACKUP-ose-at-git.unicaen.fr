<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatService;

/**
 * Description of FormuleResultatServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatServiceAwareTrait
{
    /**
     * @var FormuleResultatService
     */
    private $formuleResultatService;





    /**
     * @param FormuleResultatService $formuleResultatService
     * @return self
     */
    public function setFormuleResultatService( FormuleResultatService $formuleResultatService = null )
    {
        $this->formuleResultatService = $formuleResultatService;
        return $this;
    }



    /**
     * @return FormuleResultatService
     */
    public function getFormuleResultatService()
    {
        return $this->formuleResultatService;
    }
}