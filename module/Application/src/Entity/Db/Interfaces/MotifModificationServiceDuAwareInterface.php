<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\MotifModificationServiceDu;

/**
 * Description of MotifModificationServiceDuAwareInterface
 *
 * @author UnicaenCode
 */
interface MotifModificationServiceDuAwareInterface
{
    /**
     * @param MotifModificationServiceDu $motifModificationServiceDu
     * @return self
     */
    public function setMotifModificationServiceDu( MotifModificationServiceDu $motifModificationServiceDu = null );



    /**
     * @return MotifModificationServiceDu
     */
    public function getMotifModificationServiceDu();
}