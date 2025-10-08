<?php

namespace Intervenant\Assertion;

use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Framework\Authorize\AbstractAssertion;
use Intervenant\Entity\Db\Note;
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
    const PRIV_EDITER_NOTE    = 'intervenant-editer-note';



    protected function assertEntity(ResourceInterface $entity, $privilege = null): bool
    {
        switch (true) {
            case $entity instanceof Note:
                switch ($privilege) {
                    case self::PRIV_SUPPRIMER_NOTE:
                        return $this->assertSuppressionNote($entity);
                    case self::PRIV_EDITER_NOTE:
                        return $this->assertEditionNote($entity);
                }
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
    protected function assertController(string $controller, ?string $action): bool
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



    protected function assertSuppressionNote(?Note $note = null): bool
    {
        if (empty($note)) {
            return false;
        }

        if ($this->authorize->isAllowedPrivilege(Privileges::INTERVENANT_NOTE_ADMINISTRATION)) {
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

        if ($this->authorize->isAllowedPrivilege(Privileges::INTERVENANT_NOTE_ADMINISTRATION)) {
            return true;
        }

        if ($this->getServiceContext()->getUtilisateur() == $note->getHistoCreateur()) {
            return true;
        }


        return false;


    }


}