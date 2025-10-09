<?php

namespace Plafond\Processus;


/**
 * Description of PlafondProcessusAwareTrait
 *
 * @author UnicaenCode
 */
trait PlafondProcessusAwareTrait
{
    protected ?PlafondProcessus $processusPlafond = null;



    /**
     * @param PlafondProcessus $processusPlafond
     *
     * @return self
     */
    public function setProcessusPlafond(?PlafondProcessus $processusPlafond)
    {
        $this->processusPlafond = $processusPlafond;

        return $this;
    }



    public function getProcessusPlafond(): ?PlafondProcessus
    {
        if (empty($this->processusPlafond)) {
            $this->processusPlafond =\Unicaen\Framework\Application\Application::getInstance()->container()->get(PlafondProcessus::class);
        }

        return $this->processusPlafond;
    }
}