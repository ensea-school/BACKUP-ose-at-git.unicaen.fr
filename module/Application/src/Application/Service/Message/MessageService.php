<?php
/**
 * Created by PhpStorm.
 * User: gauthierb
 * Date: 15/07/15
 * Time: 17:46
 */

namespace Application\Service\Message;


use Application\Traits\RoleAwareTrait;
use UnicaenApp\Util;
use Zend\Permissions\Acl\Role\RoleInterface;

class MessageService
{
    use RoleAwareTrait;

    private $messageRepo;

    public function __construct(MessageRepository $messageTemplateRepo)
    {
        $this->messageRepo = $messageTemplateRepo;
    }

    public function render($messageId, array $parameters = [], RoleInterface $role = null)
    {
        $this->messageRepo->setRole($role ?: $this->getRole());

        $content = $this->messageRepo->messageContentById($messageId);

        return MessageFormatter::format($content, $parameters);
    }
}



class MessageFormatter
{
    static public function format($messageTemplate, array $parameters = [])
    {
        return Util::tokenReplacedString($messageTemplate, self::normalizedParameters($parameters));
    }

    static private function normalizedParameters(array $parameters = [])
    {
        $normalizedParameters = $parameters;

        foreach ($parameters as $name => $value) {
            if ($value instanceof \DateTime) {
                $normalizedParameters[$name] = $value->format(Constants::DATETIME_FORMAT);
            }
        }

        return $normalizedParameters;
    }
}