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
    /**
     * @var TblAgrementService
     */
    private $serviceTblAgrement;



    /**
     * @param TblAgrementService $serviceTblAgrement
     *
     * @return self
     */
    public function setServiceTblAgrement(TblAgrementService $serviceTblAgrement)
    {
        $this->serviceTblAgrement = $serviceTblAgrement;

        return $this;
    }



    /**
     * @return TblAgrementService
     */
    public function getServiceTblAgrement()
    {
        if (empty($this->serviceTblAgrement)) {
            $this->serviceTblAgrement = \Application::$container->get('ApplicationTblAgrement');
        }

        return $this->serviceTblAgrement;
    }
}