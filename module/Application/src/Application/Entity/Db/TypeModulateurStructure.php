<?php

namespace Application\Entity\Db;

/**
 * TypeModulateurStructure
 */
class TypeModulateurStructure implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeModulateur
     */
    protected $typeModulateur;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;



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
     * Set typeModulateur
     *
     * @param \Application\Entity\Db\TypeModulateur $typeModulateur
     *
     * @return TypeModulateurStructure
     */
    public function setTypeModulateur(\Application\Entity\Db\TypeModulateur $typeModulateur = null)
    {
        $this->typeModulateur = $typeModulateur;

        return $this;
    }



    /**
     * Get typeModulateur
     *
     * @return \Application\Entity\Db\TypeModulateur
     */
    public function getTypeModulateur()
    {
        return $this->typeModulateur;
    }



    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     *
     * @return TypeModulateurStructure
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
}
