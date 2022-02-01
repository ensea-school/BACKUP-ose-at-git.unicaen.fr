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
    protected ?MotifModificationServiceDu $entityDbMotifModificationServiceDu;



    /**
     * @param MotifModificationServiceDu|null $entityDbMotifModificationServiceDu
     *
     * @return self
     */
    public function setEntityDbMotifModificationServiceDu( ?MotifModificationServiceDu $entityDbMotifModificationServiceDu )
    {
        $this->entityDbMotifModificationServiceDu = $entityDbMotifModificationServiceDu;

        return $this;
    }



    public function getEntityDbMotifModificationServiceDu(): ?MotifModificationServiceDu
    {
        if (!$this->entityDbMotifModificationServiceDu){
            $this->entityDbMotifModificationServiceDu = \Application::$container->get(MotifModificationServiceDu::class);
        }

        return $this->entityDbMotifModificationServiceDu;
    }
}