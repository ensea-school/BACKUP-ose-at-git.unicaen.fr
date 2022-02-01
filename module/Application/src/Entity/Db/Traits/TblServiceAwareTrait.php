<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TblService;

/**
 * Description of TblServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblServiceAwareTrait
{
    protected ?TblService $entityDbTblService;



    /**
     * @param TblService|null $entityDbTblService
     *
     * @return self
     */
    public function setEntityDbTblService( ?TblService $entityDbTblService )
    {
        $this->entityDbTblService = $entityDbTblService;

        return $this;
    }



    public function getEntityDbTblService(): ?TblService
    {
        if (!$this->entityDbTblService){
            $this->entityDbTblService = \Application::$container->get(TblService::class);
        }

        return $this->entityDbTblService;
    }
}