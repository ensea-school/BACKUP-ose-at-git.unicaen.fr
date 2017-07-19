<?php

namespace Application\Entity\Db;

use UnicaenAuth\Entity\Db\AbstractUser;


/**
 * Utilisateur
 */
class Utilisateur extends AbstractUser
{
    const APP_UTILISATEUR_ID = 1;



    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return [];
    }

}
