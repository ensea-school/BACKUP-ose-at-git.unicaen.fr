<?php

namespace Intervenant\Service;


/**
 * Description of NoteServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait NoteServiceAwareTrait
{
    protected ?NoteService $serviceNote = null;



    /**
     * @param NoteService $serviceNote
     *
     * @return self
     */
    public function setServiceNote(?NoteService $serviceNote)
    {
        $this->serviceNote = $serviceNote;

        return $this;
    }



    public function getServiceNote(): ?NoteService
    {
        if (empty($this->serviceNote)) {
            $this->serviceNote = \Framework\Application\Application::getInstance()->container()->get(NoteService::class);
        }

        return $this->serviceNote;
    }
}