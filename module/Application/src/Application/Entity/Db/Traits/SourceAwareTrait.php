<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Source;

/**
 * Description of SourceAwareTrait
 *
 * @author UnicaenCode
 */
trait SourceAwareTrait
{
    /**
     * @var Source
     */
    private $source;





    /**
     * @param Source $source
     * @return self
     */
    public function setSource( Source $source = null )
    {
        $this->source = $source;
        return $this;
    }



    /**
     * @return Source
     */
    public function getSource()
    {
        return $this->source;
    }
}