<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * Fonction
 *
 * @ORM\Table(name="FONCTION", indexes={@ORM\Index(name="IDX_CF3349F1B16F9F85", columns={"PERSONNEL_ID"}), @ORM\Index(name="IDX_CF3349F1884B0F7B", columns={"STRUCTURE_ID"}), @ORM\Index(name="IDX_CF3349F1C2443469", columns={"TYPE_ID"})})
 * @ORM\Entity
 */
class Fonction
{
    /**
     * @var \Application\Entity\Db\Personnel
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Personnel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="PERSONNEL_ID", referencedColumnName="ID")
     * })
     */
    private $personnel;

    /**
     * @var \Application\Entity\Db\Structure
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\Structure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="STRUCTURE_ID", referencedColumnName="ID")
     * })
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\TypeFonction
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\OneToOne(targetEntity="Application\Entity\Db\TypeFonction")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="TYPE_ID", referencedColumnName="ID")
     * })
     */
    private $type;



    /**
     * Set personnel
     *
     * @param \Application\Entity\Db\Personnel $personnel
     * @return Fonction
     */
    public function setPersonnel(\Application\Entity\Db\Personnel $personnel)
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Fonction
     */
    public function setStructure(\Application\Entity\Db\Structure $structure)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set type
     *
     * @param \Application\Entity\Db\TypeFonction $type
     * @return Fonction
     */
    public function setType(\Application\Entity\Db\TypeFonction $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeFonction 
     */
    public function getType()
    {
        return $this->type;
    }
}
