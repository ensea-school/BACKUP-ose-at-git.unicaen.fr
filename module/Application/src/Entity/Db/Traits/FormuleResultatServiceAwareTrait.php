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
    protected ?FormuleResultatService $formuleResultatService = null;



    /**
     * @param FormuleResultatService $formuleResultatService
     *
     * @return self
     */
    public function setFormuleResultatService( FormuleResultatService $formuleResultatService )
    {
        $this->formuleResultatService = $formuleResultatService;

        return $this;
    }



    public function getFormuleResultatService(): ?FormuleResultatService
    {
        return $this->formuleResultatService;
    }
}