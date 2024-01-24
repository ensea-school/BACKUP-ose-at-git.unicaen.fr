<?php

namespace Formule\Entity\Db;

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
    public function setFormuleResultatService( ?FormuleResultatService $formuleResultatService )
    {
        $this->formuleResultatService = $formuleResultatService;

        return $this;
    }



    public function getFormuleResultatService(): ?FormuleResultatService
    {
        return $this->formuleResultatService;
    }
}