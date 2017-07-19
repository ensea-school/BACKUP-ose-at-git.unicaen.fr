<?php

namespace Application\Service\Interfaces;

use Application\Service\TblAgrementService;
use RuntimeException;

/**
 * Description of TblAgrementServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface TblAgrementServiceAwareInterface
{
    /**
     * @param TblAgrementService $serviceTblAgrement
     * @return self
     */
    public function setServiceTblAgrement( TblAgrementService $serviceTblAgrement );



    /**
     * @return TblAgrementServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTblAgrement();
}