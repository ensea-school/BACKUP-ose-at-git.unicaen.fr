<?php

namespace Plafond\Service;


/**
 * Description of PlafondServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondServiceAwareTrait
{
    protected ?PlafondService $servicePlafond;



    /**
     * @param PlafondService|null $servicePlafond
     *
     * @return self
     */
    public function setServicePlafond( ?PlafondService $servicePlafond )
    {
        $this->servicePlafond = $servicePlafond;

        return $this;
    }



    public function getServicePlafond(): ?PlafondService
    {
        if (!$this->servicePlafond){
            $this->servicePlafond = \Application::$container->get(PlafondService::class);
        }

        return $this->servicePlafond;
    }
}