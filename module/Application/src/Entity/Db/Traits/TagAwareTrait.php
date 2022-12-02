<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Tag;

/**
 * Description of TagAwareTrait
 *
 * @author UnicaenCode
 */
trait TagAwareTrait
{
    protected ?Tag $tag = null;


    /**
     * @param Tag $tag
     *
     * @return self
     */
    public function setTag(?Tag $tag)
    {
        $this->tag = $tag;

        return $this;
    }


    public function getTag(): ?Tag
    {
        return $this->tag;
    }
}