<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Personnel
 *
 * @ORM\Table(name="PERSONNEL")
 * @ORM\Entity
 */
class Personnel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="ID", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="PERSONNEL_ID_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="LOGIN", type="string", length=50, nullable=false)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=60, nullable=false)
     */
    private $nom;

    /**
     * @var integer
     *
     * @ORM\Column(name="PERSONNEL_ID", type="integer", nullable=false)
     */
    private $personnelId;

    /**
     * @var string
     *
     * @ORM\Column(name="PRENOM", type="string", length=60, nullable=false)
     */
    private $prenom;



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
     * Set login
     *
     * @param string $login
     * @return Personnel
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Personnel
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set personnelId
     *
     * @param integer $personnelId
     * @return Personnel
     */
    public function setPersonnelId($personnelId)
    {
        $this->personnelId = $personnelId;

        return $this;
    }

    /**
     * Get personnelId
     *
     * @return integer 
     */
    public function getPersonnelId()
    {
        return $this->personnelId;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Personnel
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
}
