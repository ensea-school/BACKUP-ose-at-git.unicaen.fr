<?php

namespace Agrement\Service\Traits;

use Agrement\Service\TblAgrementService;

/**
 * Description of TblAgrementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TblAgrementServiceAwareTrait
{
    protected ?TblAgrementService $serviceTblAgrement = null;



    /**
     * @param TblAgrementService $serviceTblAgrement
     *
     * @return self
     */
    public function setServiceTblAgrement(?TblAgrementService $serviceTblAgrement)
    {
        $this->serviceTblAgrement = $serviceTblAgrement;

        return $this;
    }



    public function getServiceTblAgrement(): ?TblAgrementService
    {
        if (empty($this->serviceTblAgrement)) {
            $this->serviceTblAgrement =\Unicaen\Framework\Application\Application::getInstance()->container()->get(TblAgrementService::class);
        }

        return $this->serviceTblAgrement;
    }
}