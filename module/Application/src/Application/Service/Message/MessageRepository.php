<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 15/07/15
 * Time: 16:55
 */

namespace Application\Service\Message;


use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Traits\RoleAwareTrait;

class MessageRepository
{
    use RoleAwareTrait;

    private $messages;

    public function __construct(array $config)
    {
        $this->loadFromConfig($config);
    }

    private function loadFromConfig(array $config)
    {
        foreach ($config as $data) {
            $messageId = $data['id'];
            $contents  = $data['contents'];
            $message   = new Message($contents);

            $this->messages[$messageId] = $message;
        }

        return $this;
    }

    /**
     * @param $messageId
     * @return string
     */
    public function messageContentById($messageId)
    {
        $message = $this->messageById($messageId);
        $roleId = $this->roleIdOfCurrentRole();

        return $message->getContent($roleId);
    }

    /**
     * @param $messageId
     * @return Message
     */
    public function messageById($messageId)
    {
        if (!isset($this->messages[$messageId])) {
            throw new \RuntimeException("Message introuvable avec l'id '$messageId'.");
        }

        return $this->messages[$messageId];
    }

    private function assertRoleSpecified()
    {
        if (!$this->getRole()) {
            throw new \RuntimeException("Aucun rôle n'a été spécifié.");
        }

        return $this;
    }

    private function roleIdOfCurrentRole()
    {
        $this->assertRoleSpecified();

        $role = $this->getRole();

        if ($role instanceof IntervenantRole) {
            $roleId = IntervenantRole::ROLE_ID;
        } else {
            $roleId = ComposanteRole::ROLE_ID;
        }

        return $roleId;
    }
}