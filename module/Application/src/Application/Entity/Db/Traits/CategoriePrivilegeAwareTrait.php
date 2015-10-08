<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\CategoriePrivilege;

/**
 * Description of CategoriePrivilegeAwareTrait
 *
 * @author UnicaenCode
 */
trait CategoriePrivilegeAwareTrait
{
    /**
     * @var CategoriePrivilege
     */
    private $categoriePrivilege;





    /**
     * @param CategoriePrivilege $categoriePrivilege
     * @return self
     */
    public function setCategoriePrivilege( CategoriePrivilege $categoriePrivilege = null )
    {
        $this->categoriePrivilege = $categoriePrivilege;
        return $this;
    }



    /**
     * @return CategoriePrivilege
     */
    public function getCategoriePrivilege()
    {
        return $this->categoriePrivilege;
    }
}