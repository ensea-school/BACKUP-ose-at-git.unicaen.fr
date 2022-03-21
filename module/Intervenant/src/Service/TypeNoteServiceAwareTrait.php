<?php

namespace Intervenant\Service;


/**
 * Description of TypeNoteServiceAwareTrait
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
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
            $this->serviceTypeNote = \Application::$container->get(TypeNoteService::class);
        }

        return $this->serviceTypeNote;
    }
}