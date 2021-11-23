<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\WfDepBloquante;

/**
 * Description of WfDepBloquanteAwareTrait
 *
 * @author UnicaenCode
 */
trait WfDepBloquanteAwareTrait
{
    /**
     * @var WfDepBloquante
     */
    private $wfDepBloquante;





    /**
     * @param WfDepBloquante $wfDepBloquante
     * @return self
     */
    public function setWfDepBloquante( WfDepBloquante $wfDepBloquante = null )
    {
        $this->wfDepBloquante = $wfDepBloquante;
        return $this;
    }



    /**
     * @return WfDepBloquante
     */
    public function getWfDepBloquante()
    {
        return $this->wfDepBloquante;
    }
}