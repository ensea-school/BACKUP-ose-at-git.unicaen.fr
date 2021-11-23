<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\MotifModificationServiceDu;

/**
 * Description of MotifModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifModificationServiceDuAwareTrait
{
    /**
     * @var MotifModificationServiceDu
     */
    private $motifModificationServiceDu;





    /**
     * @param MotifModificationServiceDu $motifModificationServiceDu
     * @return self
     */
    public function setMotifModificationServiceDu( MotifModificationServiceDu $motifModificationServiceDu = null )
    {
        $this->motifModificationServiceDu = $motifModificationServiceDu;
        return $this;
    }



    /**
     * @return MotifModificationServiceDu
     */
    public function getMotifModificationServiceDu()
    {
        return $this->motifModificationServiceDu;
    }
}