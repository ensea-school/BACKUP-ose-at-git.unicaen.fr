<?php

namespace Application\Service\Traits;

use Application\Service\TagService;

/**
 * Description of TagServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TagServiceAwareTrait
{
    protected ?TagService $serviceTag = null;


    /**
     * @param TagService $serviceTag
     *
     * @return self
     */
    public function setServiceTag(?TagService $serviceTag)
    {
        $this->serviceTag = $serviceTag;

        return $this;
    }


    public function getServiceTag(): ?TagService
    {
        if (empty($this->serviceTag)) {
            $this->serviceTag = \Application::$container->get(TagService::class);
        }

        return $this->serviceTag;
    }
}