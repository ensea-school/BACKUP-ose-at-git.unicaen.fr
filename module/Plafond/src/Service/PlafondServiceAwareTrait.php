<?php

namespace Plafond\Service;


/**
 * Description of PlafondServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondServiceAwareTrait
{
    protected ?PlafondService $servicePlafond = null;



    /**
     * @param PlafondService $servicePlafond
     *
     * @return self
     */
    public function setServicePlafond(?PlafondService $servicePlafond)
    {
        $this->servicePlafond = $servicePlafond;

        return $this;
    }



    public function getServicePlafond(): ?PlafondService
    {
        if (empty($this->servicePlafond)) {
            $this->servicePlafond =\Unicaen\Framework\Application\Application::getInstance()->container()->get(PlafondService::class);
        }

        return $this->servicePlafond;
    }
}