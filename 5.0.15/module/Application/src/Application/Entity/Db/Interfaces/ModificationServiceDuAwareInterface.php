<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\ModificationServiceDu;

/**
 * Description of ModificationServiceDuAwareInterface
 *
 * @author UnicaenCode
 */
interface ModificationServiceDuAwareInterface
{
    /**
     * @param ModificationServiceDu $modificationServiceDu
     * @return self
     */
    public function setModificationServiceDu( ModificationServiceDu $modificationServiceDu = null );



    /**
     * @return ModificationServiceDu
     */
    public function getModificationServiceDu();
}