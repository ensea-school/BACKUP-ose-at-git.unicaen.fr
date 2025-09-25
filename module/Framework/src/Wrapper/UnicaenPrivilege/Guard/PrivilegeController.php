<?php

namespace UnicaenPrivilege\Guard;

use Framework\Authorize\Authorize;

class PrivilegeController
{
    public static function getResourceId($controller, $action = null) : string
    {
        return Authorize::controllerResource($controller, $action);
    }
}