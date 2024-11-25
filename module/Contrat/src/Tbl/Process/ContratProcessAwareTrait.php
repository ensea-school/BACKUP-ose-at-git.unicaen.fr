<?php

namespace Contrat\Tbl\Process;



trait ContratProcessAwareTrait
{
    protected ?ContratProcess $tblProcessContratProcess = null;



    public function getTblProcessContratProcess(): ?ContratProcess
    {
        return $this->tblProcessContratProcess;
    }



    /**
     * @param ContratProcess $tblProcessContratProcess
     *
     * @return self
     */
    public function setTblProcessContratProcess(?ContratProcess $tblProcessContratProcess)
    {
        $this->tblProcessContratProcess = $tblProcessContratProcess;

        return $this;
    }
}