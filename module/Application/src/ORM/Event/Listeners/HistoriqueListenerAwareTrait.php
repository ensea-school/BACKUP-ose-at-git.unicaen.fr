<?php

namespace Application\ORM\Event\Listeners;


/**
 * Description of HistoriqueListenerAwareTrait
 *
 * @author UnicaenCode
 */
trait HistoriqueListenerAwareTrait
{
    protected ?HistoriqueListener $oRMEventListenersHistoriqueListener = null;



    /**
     * @param HistoriqueListener $oRMEventListenersHistoriqueListener
     *
     * @return self
     */
    public function setORMEventListenersHistoriqueListener(?HistoriqueListener $oRMEventListenersHistoriqueListener)
    {
        $this->oRMEventListenersHistoriqueListener = $oRMEventListenersHistoriqueListener;

        return $this;
    }



    public function getORMEventListenersHistoriqueListener(): ?HistoriqueListener
    {
        if (empty($this->oRMEventListenersHistoriqueListener)) {
            $this->oRMEventListenersHistoriqueListener = \Unicaen\Framework\Application\Application::getInstance()->container()->get(HistoriqueListener::class);
        }

        return $this->oRMEventListenersHistoriqueListener;
    }
}

