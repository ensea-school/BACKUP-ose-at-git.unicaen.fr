<?php

namespace Service\Service;


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
            $this->serviceTag = \AppAdmin::container()->get(TagService::class);
        }

        return $this->serviceTag;
    }
}