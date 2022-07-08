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
    protected ?ModificationServiceDu $modificationServiceDu = null;



    /**
     * @param ModificationServiceDu $modificationServiceDu
     *
     * @return self
     */
    public function setModificationServiceDu( ?ModificationServiceDu $modificationServiceDu )
    {
        $this->modificationServiceDu = $modificationServiceDu;

        return $this;
    }



    public function getModificationServiceDu(): ?ModificationServiceDu
    {
        return $this->modificationServiceDu;
    }
}