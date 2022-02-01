<?php

namespace Application\Service\Traits;

use Application\Service\TblAgrementService;

/**
 * Description of TblAgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblAgrementServiceAwareTrait
{
    protected ?TblAgrementService $serviceTblAgrement;



    /**
     * @param TblAgrementService|null $serviceTblAgrement
     *
     * @return self
     */
    public function setServiceTblAgrement( ?TblAgrementService $serviceTblAgrement )
    {
        $this->serviceTblAgrement = $serviceTblAgrement;

        return $this;
    }



    public function getServiceTblAgrement(): ?TblAgrementService
    {
        if (!$this->serviceTblAgrement){
            $this->serviceTblAgrement = \Application::$container->get(TblAgrementService::class);
        }

        return $this->serviceTblAgrement;
    }
}