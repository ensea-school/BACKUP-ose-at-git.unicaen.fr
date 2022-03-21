<?php

namespace Intervenant\Service;


/**
 * Description of NoteServiceAwareTrait
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
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
            $this->serviceNote = \Application::$container->get(NoteService::class);
        }

        return $this->serviceNote;
    }
}