<?php

namespace Application\Service\Message\View\Helper;

use Application\Service\Message\MessageService;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MessageHelper extends AbstractHelper
{
    private $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * @return $this
     */
    public function __invoke()
    {
        return $this;
    }

    /**
     * @param string $messageId
     * @param array $parameters
     * @param RoleInterface $role
     * @return string
     */
    public function render($messageId, array $parameters = [], RoleInterface $role = null)
    {
        return $this->messageService->render($messageId, $parameters, $role);
    }
}