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
     * @var string|null
     */
    protected $code;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $affectation;



    /**
     *
     */
    public function __construct()
    {
        $this->affectation = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return [];
    }



    /**
     * @return null|string
     */
    public function getCode()
    {
        return $this->code;
    }



    /**
     * @param null|string $code
     *
     * @return Utilisateur
     */
    public function setCode($code): Utilisateur
    {
        $this->code = $code;

        return $this;
    }



    /**
     * Add affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     *
     * @return self
     */
    public function addAffectation(\Application\Entity\Db\Affectation $affectation)
    {
        $this->affectation[] = $affectation;

        return $this;
    }



    /**
     * Remove affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     */
    public function removeAffectation(\Application\Entity\Db\Affectation $affectation)
    {
        $this->affectation->removeElement($affectation);
    }



    /**
     * Get affectation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAffectation()
    {
        return $this->affectation;
    }



    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        return $this->getDisplayName();
    }

}
