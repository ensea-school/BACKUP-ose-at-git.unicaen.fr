<?php

namespace Plafond\Service;

/**
 * Description of PlafondServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondServiceAwareTrait
{
    /**
     * @var PlafondService
     */
    protected $servicePlafond;



    /**
     * @param PlafondService $servicePlafond
     *
     * @return self
     */
    public function setServicePlafond(PlafondService $servicePlafond)
    {
        $this->servicePlafond = $servicePlafond;

        return $this;
    }



    /**
     * @return PlafondService
     */
    public function getServicePlafond(): PlafondService
    {
        if (!$this->servicePlafond) {
            $this->servicePlafond = \Application::$container->get(PlafondService::class);
        }

        return $this->servicePlafond;
    }
}