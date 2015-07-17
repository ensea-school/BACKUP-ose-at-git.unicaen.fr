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
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    protected $personnel;



    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return Utilisateur
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Set personnel
     *
     * @param \Application\Entity\Db\Personnel $personnel
     *
     * @return Utilisateur
     */
    public function setPersonnel(\Application\Entity\Db\Personnel $personnel = null)
    {
        $this->personnel = $personnel;

        return $this;
    }



    /**
     * Get personnel
     *
     * @return \Application\Entity\Db\Personnel
     */
    public function getPersonnel()
    {
        return $this->personnel;
    }



    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return [];
    }



    /**
     * @since PHP 5.6.0
     * This method is called by var_dump() when dumping an object to get the properties that should be shown.
     * If the method isn't defined on an object, then all public, protected and private properties will be shown.
     *
     * @return array
     * @link  http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.debuginfo
     */
    function __debugInfo()
    {
        return [
            'id'          => $this->id,
            'displayName' => $this->displayName,
        ];
    }

}
