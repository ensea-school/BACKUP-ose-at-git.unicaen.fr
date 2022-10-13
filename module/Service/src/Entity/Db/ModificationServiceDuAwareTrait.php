<?php

namespace Service\Entity\Db;

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
    public function setModificationServiceDu(?ModificationServiceDu $modificationServiceDu)
    {
        $this->modificationServiceDu = $modificationServiceDu;

        return $this;
    }



    public function getModificationServiceDu(): ?ModificationServiceDu
    {
        return $this->modificationServiceDu;
    }
}