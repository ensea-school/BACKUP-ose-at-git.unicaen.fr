<?php

namespace Intervenant\Service;


/**
 * Description of TypeNoteServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeNoteServiceAwareTrait
{
    protected ?TypeNoteService $serviceTypeNote = null;



    /**
     * @param TypeNoteService $serviceTypeNote
     *
     * @return self
     */
    public function setServiceTypeNote(?TypeNoteService $serviceTypeNote)
    {
        $this->serviceTypeNote = $serviceTypeNote;

        return $this;
    }



    public function getServiceTypeNote(): ?TypeNoteService
    {
        if (empty($this->serviceTypeNote)) {
            $this->serviceTypeNote = \Unicaen\Framework\Application\Application::getInstance()->container()->get(TypeNoteService::class);
        }

        return $this->serviceTypeNote;
    }
}