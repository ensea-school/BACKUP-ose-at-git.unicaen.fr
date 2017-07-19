<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\TblAgrement;

/**
 * Description of TblAgrementAwareInterface
 *
 * @author UnicaenCode
 */
interface TblAgrementAwareInterface
{
    /**
     * @param TblAgrement $tblAgrement
     * @return self
     */
    public function setTblAgrement( TblAgrement $tblAgrement = null );



    /**
     * @return TblAgrement
     */
    public function getTblAgrement();
}