<?php

namespace Application\Entity\Db;

/**
 * Affectation
 */
class Affectation implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var string
     */
    protected $sourceCode;

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
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\Personnel
     */
    protected $personnel;



    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return Affectation
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     *
     * @return Affectation
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }



    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source
     */
    public function getSource()
    {
        return $this->source;
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
