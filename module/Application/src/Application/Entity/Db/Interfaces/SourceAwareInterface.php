<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Source;

/**
 * Description of SourceAwareInterface
 *
 * @author UnicaenCode
 */
interface SourceAwareInterface
{
    /**
     * @param Source $source
     * @return self
     */
    public function setSource( Source $source = null );



    /**
     * @return Source
     */
    public function getSource();
}