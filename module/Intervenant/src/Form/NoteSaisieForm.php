<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Intervenant\Entity\Db\Note;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Intervenant\Service\NoteServiceAwareTrait;
use Intervenant\Service\TypeNoteServiceAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteSaisieForm extends AbstractForm
{
    use NoteServiceAwareTrait;
    use TypeNoteServiceAwareTrait;

    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->spec(Note::class, ['intervenant']);
        $this->spec(['contenu' => ['type' => 'Textarea']]);

        $this->build();

        $this->setAttribute('class', 'note');
        $this->addSubmit();

       
        $this->setValueOptions('type', $this->getServiceTypeNote()->getList());

        return $this;
    }

}
