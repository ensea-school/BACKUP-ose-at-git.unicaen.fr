<?php

namespace UnicaenPrivilege\Provider\Privilege;

use Framework\Authorize\Authorize;

class Privileges {

    public static function getResourceId($privilege)
    {
        return Authorize::privilegeResource($privilege);
    }

}