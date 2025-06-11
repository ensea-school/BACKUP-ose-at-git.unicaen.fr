<?php

namespace Intervenant\Assertion;

use Application\Acl\Role;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Intervenant\Entity\Db\Note;
use UnicaenPrivilege\Assertion\AbstractAssertion;
use Laminas\Permissions\Acl\Resource\ResourceInterface;


/**
 * Description of NoteService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class NoteAssertion extends AbstractAssertion
{
    use ContextServiceAwareTrait;

    const PRIV_SUPPRIMER_NOTE = 'intervenant-supprimer-note';
    const PRIV_EDITER_NOTE = 'intervenant-editer-note';

    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {

        $localPrivs = [
            self::PRIV_SUPPRIMER_NOTE,
            self::PRIV_EDITER_NOTE,

        ];


        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;


        switch (true) {
            case $entity instanceof Note:
                switch ($privilege) {
                    case self::PRIV_SUPPRIMER_NOTE:
                        return $this->assertSuppressionNote($entity);
                        break;
                    case self::PRIV_EDITER_NOTE:
                        return $this->assertEditionNote($entity);
                        break;

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
    protected function assertController($controller, $action = null, $privilege = null): bool
    {


        $role = $this->getRole();

        // Si le rôle n'est pas renseigné alors on s'en va...
        if (!$role instanceof Role) return false;

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


    protected function assertSuppressionNote(?Note $note = null): bool
    {
        if (empty($note)) {
            return false;
        }

        if ($this->getRole()->hasPrivilege(Privileges::INTERVENANT_NOTE_ADMINISTRATION)) {
            return true;
        }

        if ($note->getType()->getCode() == 'email') {
            return false;
        }

        if ($this->getServiceContext()->getUtilisateur() == $note->getHistoCreateur()) {
            return true;
        }


        return false;
    }

    protected function assertEditionNote(?Note $note = null): bool
    {
        if (empty($note)) return false;

        //Si type note email alors je ne peux pas la modifier
        if ($note->getType()->getCode() == 'email') {
            return false;
        }

        if ($this->getRole()->hasPrivilege(Privileges::INTERVENANT_NOTE_ADMINISTRATION)) {
            return true;
        }

        if ($this->getServiceContext()->getUtilisateur() == $note->getHistoCreateur()) {
            return true;
        }


        return false;


    }


}