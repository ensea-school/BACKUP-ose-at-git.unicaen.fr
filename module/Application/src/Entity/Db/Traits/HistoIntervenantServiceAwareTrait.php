<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\HistoIntervenantService;

/**
 * Description of HistoIntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait HistoIntervenantServiceAwareTrait
{
    protected ?HistoIntervenantService $entityDbHistoIntervenantService;



    /**
     * @param HistoIntervenantService|null $entityDbHistoIntervenantService
     *
     * @return self
     */
    public function setEntityDbHistoIntervenantService( ?HistoIntervenantService $entityDbHistoIntervenantService )
    {
        $this->entityDbHistoIntervenantService = $entityDbHistoIntervenantService;

        return $this;
    }



    public function getEntityDbHistoIntervenantService(): ?HistoIntervenantService
    {
        if (!$this->entityDbHistoIntervenantService){
            $this->entityDbHistoIntervenantService = \Application::$container->get(HistoIntervenantService::class);
        }

        return $this->entityDbHistoIntervenantService;
    }
}