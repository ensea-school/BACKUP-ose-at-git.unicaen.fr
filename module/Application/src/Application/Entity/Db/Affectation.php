<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * AffectationService
 */
class Affectation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\Role
     */
    protected $role;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    protected $personnel;



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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return Affectation
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
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
     * Set role
     *
     * @param \Application\Entity\Db\Role $role
     *
     * @return Affectation
     */
    public function setRole(\Application\Entity\Db\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }



    /**
     * Get role
     *
     * @return \Application\Entity\Db\Role
     */
    public function getRole()
    {
        return $this->role;
    }



    /**
     * Set personnel
     *
     * @param \Application\Entity\Db\Personnel $personnel
     *
     * @return Affectation
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

}
