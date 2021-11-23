<?php

namespace Application\Service\Traits;

use Application\Service\CcActiviteService;

/**
 * Description of CcActiviteAwareTrait
 *
 * @author UnicaenCode
 */
trait CcActiviteServiceAwareTrait
{
    /**
     * @var CcActiviteService
     */
    private $serviceCcActivite;



    /**
     * @param CcActiviteService $serviceCcActivite
     *
     * @return self
     */
    public function setServiceCcActivite(CcActiviteService $serviceCcActivite)
    {
        $this->serviceCcActivite = $serviceCcActivite;

        return $this;
    }



    /**
     * @return CcActiviteService
     */
    public function getServiceCcActivite()
    {
        if (empty($this->serviceCcActivite)) {
            $this->serviceCcActivite = \Application::$container->get(CcActiviteService::class);
        }

        return $this->serviceCcActivite;
    }
}