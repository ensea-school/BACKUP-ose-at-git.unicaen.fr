<?php

namespace Application\Service\Traits;

use Application\Service\Source;

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
    private $serviceSource;



    /**
     * @param Source $serviceSource
     *
     * @return self
     */
    public function setServiceSource(Source $serviceSource)
    {
        $this->serviceSource = $serviceSource;

        return $this;
    }



    /**
     * @return Source
     */
    public function getServiceSource()
    {
        if (empty($this->serviceSource)) {
            $this->serviceSource = \Application::$container->get('ApplicationSource');
        }

        return $this->serviceSource;
    }
}