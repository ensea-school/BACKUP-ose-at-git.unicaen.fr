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
    protected ?FormuleResultatService $entityDbFormuleResultatService;



    /**
     * @param FormuleResultatService|null $entityDbFormuleResultatService
     *
     * @return self
     */
    public function setEntityDbFormuleResultatService( ?FormuleResultatService $entityDbFormuleResultatService )
    {
        $this->entityDbFormuleResultatService = $entityDbFormuleResultatService;

        return $this;
    }



    public function getEntityDbFormuleResultatService(): ?FormuleResultatService
    {
        if (!$this->entityDbFormuleResultatService){
            $this->entityDbFormuleResultatService = \Application::$container->get('FormElementManager')->get(FormuleResultatService::class);
        }

        return $this->entityDbFormuleResultatService;
    }
}