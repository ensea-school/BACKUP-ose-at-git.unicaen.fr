<?php

namespace Application\Processus\Traits;

use Application\Processus\PlafondProcessus;

/**
 * Description of PlafondProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondProcessusAwareTrait
{
    /**
     * @var PlafondProcessus
     */
    protected $processusPlafond;



    /**
     * @param PlafondProcessus $processusPlafond
     * @return self
     */
    public function setProcessusPlafond( PlafondProcessus $processusPlafond )
    {
        $this->processusPlafond = $processusPlafond;

        return $this;
    }



    /**
     * @return PlafondProcessus
     */
    public function getProcessusPlafond() : PlafondProcessus
    {
        if (!$this->processusPlafond){
            $this->processusPlafond = \Application::$container->get(PlafondProcessus::class);
        }

        return $this->processusPlafond;
    }
}