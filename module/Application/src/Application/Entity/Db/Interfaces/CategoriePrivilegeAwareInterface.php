<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\CategoriePrivilege;

/**
 * Description of CategoriePrivilegeAwareInterface
 *
 * @author UnicaenCode
 */
interface CategoriePrivilegeAwareInterface
{
    /**
     * @param CategoriePrivilege $categoriePrivilege
     * @return self
     */
    public function setCategoriePrivilege( CategoriePrivilege $categoriePrivilege = null );



    /**
     * @return CategoriePrivilege
     */
    public function getCategoriePrivilege();
}