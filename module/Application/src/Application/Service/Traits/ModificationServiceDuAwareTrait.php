<?php

namespace Application\Service\Traits;

use Application\Service\ModificationServiceDu;

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
    private $serviceModificationServiceDu;



    /**
     * @param ModificationServiceDu $serviceModificationServiceDu
     *
     * @return self
     */
    public function setServiceModificationServiceDu(ModificationServiceDu $serviceModificationServiceDu)
    {
        $this->serviceModificationServiceDu = $serviceModificationServiceDu;

        return $this;
    }



    /**
     * @return ModificationServiceDu
     */
    public function getServiceModificationServiceDu()
    {
        if (empty($this->serviceModificationServiceDu)) {
            $this->serviceModificationServiceDu = \Application::$container->get('ApplicationModificationServiceDu');
        }

        return $this->serviceModificationServiceDu;
    }
}