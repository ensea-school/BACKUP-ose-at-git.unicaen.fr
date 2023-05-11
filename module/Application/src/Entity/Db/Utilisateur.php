<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\UserInterface;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use UnicaenVue\Axios\AxiosExtractorInterface;


/**
 * Utilisateur
 */
class Utilisateur extends AbstractUser implements UserInterface, AxiosExtractorInterface
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



    public function axiosDefinition(): array
    {
        return ['email', 'displayName'];
    }



    /**
     * @return \Laminas\Permissions\Acl\Role\RoleInterface[]
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
     * Set password.
     *
     * @param string $password
     *
     * @return void
     */
    public function setPassword($password, $encrypt = false)
    {
        if ($encrypt) {
            $bcrypt   = new \Laminas\Crypt\Password\Bcrypt();
            $password = $bcrypt->create($password);
        }
        parent::setPassword($password); // TODO: Change the autogenerated stub

        $this->setPasswordResetToken(null);
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
