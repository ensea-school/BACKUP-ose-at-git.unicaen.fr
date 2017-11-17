<?php

namespace Application\Service\Traits;

use Application\Service\LocalContext;

/**
 * Description of LocalContextAwareTrait
 *
 * @author UnicaenCode
 */
trait LocalContextAwareTrait
{
    /**
     * @var LocalContext
     */
    private $serviceLocalContext;



    /**
     * @param LocalContext $serviceLocalContext
     *
     * @return self
     */
    public function setServiceLocalContext(LocalContext $serviceLocalContext)
    {
        $this->serviceLocalContext = $serviceLocalContext;

        return $this;
    }



    /**
     * @return LocalContext
     */
    public function getServiceLocalContext()
    {
        if (empty($this->serviceLocalContext)) {
            $this->serviceLocalContext = \Application::$container->get('ApplicationLocalContext');
        }

        return $this->serviceLocalContext;
    }
}