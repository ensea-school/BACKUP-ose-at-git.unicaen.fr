<?php

namespace Intervenant\Assertion;

use Application\Entity\Db\Intervenant;
use Application\Acl\Role;
use Application\Entity\Db\WfEtape;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Note;
use UnicaenAuth\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of NoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    protected function assertEntity(ResourceInterface $entity, $privilege = null)
    {
        $here = '';
        switch (true) {
            case $entity instanceof Note:
                switch ($privilege) {
                    case Privileges::INTERVENANT_NOTE_SUPPRESSION:
                        return $this->assertSuppressionNote($entity);
                }
            break;
        }

        return true;
    }



    /**
     * @param string $controller
     * @param string $action
     * @param string $privilege
     *
     * @return boolean
     */
    protected function assertController($controller, $action = null, $privilege = null)
    {
        /**
         * @var Note $note
         */
        $note = $this->getMvcEvent()->getParam('note');

        switch ($action) {
            case 'supprimer':
                return $this->assertSuppressionNote($note);
            break;
        }

        return true;
    }



    protected function assertSuppressionNote(Note $note = null)
    {
        if (empty($note)) {
            return false;
        }

        if ($this->getServiceContext()->getUtilisateur() == $note->getHistoCreateur()) {
            return true;
        }

        if ($this->getRole()->hasPrivilege(Privileges::INTERVENANT_NOTE_SUPPRESSION)) {
            return true;
        }

        return false;
    }

}