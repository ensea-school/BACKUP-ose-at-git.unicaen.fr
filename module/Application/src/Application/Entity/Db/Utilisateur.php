<?php

namespace Application\Entity\Db;

use BjyAuthorize\Provider\Role\ProviderInterface;
use ZfcUser\Entity\UserInterface;

/**
 * Utilisateur
 */
class Utilisateur implements UserInterface, ProviderInterface
{
    const APP_UTILISATEUR_ID = 1;
    
    /**
     * @var string
     */
    protected $displayName;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var integer
     */
    protected $state;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    protected $personnel;

    /**
     * Set displayName
     *
     * @param string $displayName
     * @return Utilisateur
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;

        return $this;
    }

    /**
     * Get displayName
     *
     * @return string 
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Utilisateur
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Utilisateur
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set state
     *
     * @param integer $state
     * @return Utilisateur
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return integer 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return Utilisateur
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
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


	/**************************************************************************************************
	 * 										Début ajout
	 **************************************************************************************************/

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getDisplayName();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return Utilisateur
     */
    public function setId($id)
    {
        $this->id = $id;
        
        return $this;
    }
    
    /************************ Interface ProviderInterface ******************/
    
    /**
     * @return \Zend\Permissions\Acl\Role\RoleInterface[]
     */
    public function getRoles()
    {
        return [];
    }
}
