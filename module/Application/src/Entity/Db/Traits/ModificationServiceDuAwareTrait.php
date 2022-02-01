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
    protected ?ModificationServiceDu $entityDbModificationServiceDu;



    /**
     * @param ModificationServiceDu|null $entityDbModificationServiceDu
     *
     * @return self
     */
    public function setEntityDbModificationServiceDu( ?ModificationServiceDu $entityDbModificationServiceDu )
    {
        $this->entityDbModificationServiceDu = $entityDbModificationServiceDu;

        return $this;
    }



    public function getEntityDbModificationServiceDu(): ?ModificationServiceDu
    {
        if (!$this->entityDbModificationServiceDu){
            $this->entityDbModificationServiceDu = \Application::$container->get(ModificationServiceDu::class);
        }

        return $this->entityDbModificationServiceDu;
    }
}