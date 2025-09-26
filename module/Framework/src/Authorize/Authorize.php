<?php

namespace Framework\Authorize;

class Authorize
{

    public function __construct(
        private readonly \BjyAuthorize\Service\Authorize $oldAuthorize
    )
    {

    }



    public function isAllowed(string $resource): bool
    {
        return $this->oldAuthorize->isAllowed($resource);
    }



    public static function controllerResource(string $controller, ?string $action = null): string
    {
        if (isset($action)) {
            return sprintf('controller/%s:%s', $controller, strtolower($action));
        }

        return sprintf('controller/%s', $controller);
    }



    public static function privilegeResource(string|object $privilege): string
    {
        if (is_object($privilege) && method_exists($privilege, 'getFullCode')) {
            $privilege = $privilege->getFullCode();

        }

        return 'privilege/' . $privilege;
    }
}