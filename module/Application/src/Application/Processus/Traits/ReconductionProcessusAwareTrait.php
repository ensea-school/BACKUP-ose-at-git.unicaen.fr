<?php

namespace Application\Processus\Traits;


use Application\Processus\ReconductionProcessus;

/**
 * Description of ReconductionProcessusAwareTrait
 *
 * @author LECOURTES Anthony <antony.lecourtes@unicaen.fr>
 */
trait ReconductionProcessusAwareTrait
{
    /**
     * @var ReconductionProcessus
     */
    private $reconductionProcessus;



    /**
     * @param ReconductionProcessus $reconductionProcessus
     *
     * @return self
     */
    public function setProcessusReconduction(ReconductionProcessus $reconductionProcessus)
    {
        $this->reconductionProcessus = $reconductionProcessus;

        return $this;
    }



    /**
     * @return ReconductionProcessus
     */
    public function getProcessusReconduction()
    {
        if (empty($this->reconductionProcessus)) {
            $this->reconductionProcessus = \Application::$container->get(ReconductionProcessus::class);
        }

        return $this->reconductionProcessus;
    }
}