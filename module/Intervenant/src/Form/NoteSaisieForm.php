<?php

namespace Intervenant\Form;

use Application\Form\AbstractForm;
use Intervenant\Entity\Db\Note;
use Intervenant\Service\TypeNoteServiceAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteSaisieForm extends AbstractForm
{
    use TypeNoteServiceAwareTrait;

    public function init()
    {

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->spec(Note::class, ['intervenant']);
        $this->spec(['contenu' => ['type' => 'Textarea']]);

        $this->build();

        $this->setAttribute('class', 'note');
        $this->addSecurity();
        $this->addSubmit();

        $qb = $this->getServiceTypeNote()->findDefaultCode();
        $this->setValueOptions('type', $this->getServiceTypeNote()->getList($qb));

        return $this;
    }

}
