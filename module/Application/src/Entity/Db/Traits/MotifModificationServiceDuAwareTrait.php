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
    protected ?MotifModificationServiceDu $motifModificationServiceDu = null;



    /**
     * @param MotifModificationServiceDu $motifModificationServiceDu
     *
     * @return self
     */
    public function setMotifModificationServiceDu( MotifModificationServiceDu $motifModificationServiceDu )
    {
        $this->motifModificationServiceDu = $motifModificationServiceDu;

        return $this;
    }



    public function getMotifModificationServiceDu(): ?MotifModificationServiceDu
    {
        return $this->motifModificationServiceDu;
    }
}