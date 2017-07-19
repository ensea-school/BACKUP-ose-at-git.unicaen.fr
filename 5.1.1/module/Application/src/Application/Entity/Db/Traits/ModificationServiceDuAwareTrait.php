<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ModificationServiceDu;

/**
 * Description of ModificationServiceDuAwareTrait
 *
 * @author UnicaenCode
 */
trait ModificationServiceDuAwareTrait
{
    /**
     * @var ModificationServiceDu
     */
    private $modificationServiceDu;





    /**
     * @param ModificationServiceDu $modificationServiceDu
     * @return self
     */
    public function setModificationServiceDu( ModificationServiceDu $modificationServiceDu = null )
    {
        $this->modificationServiceDu = $modificationServiceDu;
        return $this;
    }



    /**
     * @return ModificationServiceDu
     */
    public function getModificationServiceDu()
    {
        return $this->modificationServiceDu;
    }
}