<?php

namespace Paiement\Tbl\Process;


/**
 * Description of PaiementProcessAwareTrait
 *
 * @author UnicaenCode
 */
trait PaiementProcessAwareTrait
{
    protected ?PaiementProcess $tblProcessPaiementProcess = null;



    /**
     * @param PaiementProcess $tblProcessPaiementProcess
     *
     * @return self
     */
    public function setTblProcessPaiementProcess( ?PaiementProcess $tblProcessPaiementProcess )
    {
        $this->tblProcessPaiementProcess = $tblProcessPaiementProcess;

        return $this;
    }



    public function getTblProcessPaiementProcess(): ?PaiementProcess
    {
        return $this->tblProcessPaiementProcess;
    }
}