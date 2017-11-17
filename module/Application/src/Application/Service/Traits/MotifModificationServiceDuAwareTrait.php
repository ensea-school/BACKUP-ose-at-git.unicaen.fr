<?php

namespace Application\Service\Traits;

use Application\Service\MotifModificationServiceDu;

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
    private $serviceMotifModificationServiceDu;



    /**
     * @param MotifModificationServiceDu $serviceMotifModificationServiceDu
     *
     * @return self
     */
    public function setServiceMotifModificationServiceDu(MotifModificationServiceDu $serviceMotifModificationServiceDu)
    {
        $this->serviceMotifModificationServiceDu = $serviceMotifModificationServiceDu;

        return $this;
    }



    /**
     * @return MotifModificationServiceDu
     */
    public function getServiceMotifModificationServiceDu()
    {
        if (empty($this->serviceMotifModificationServiceDu)) {
            $this->serviceMotifModificationServiceDu = \Application::$container->get('ApplicationMotifModificationServiceDu');
        }

        return $this->serviceMotifModificationServiceDu;
    }
}