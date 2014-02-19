<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeRoleStructure
 */
class TypeRoleStructure
{
    /**
     * @var \Application\Entity\Db\TypeStructure
     */
    private $typeStructure;

    /**
     * @var \Application\Entity\Db\TypeRole
     */
    private $typeRole;

    /**
     * @var \Application\Entity\Db\Historique
     */
    private $historique;


    /**
     * Set typeStructure
     *
     * @param \Application\Entity\Db\TypeStructure $typeStructure
     * @return TypeRoleStructure
     */
    public function setTypeStructure(\Application\Entity\Db\TypeStructure $typeStructure)
    {
        $this->typeStructure = $typeStructure;

        return $this;
    }

    /**
     * Get typeStructure
     *
     * @return \Application\Entity\Db\TypeStructure 
     */
    public function getTypeStructure()
    {
        return $this->typeStructure;
    }

    /**
     * Set typeRole
     *
     * @param \Application\Entity\Db\TypeRole $typeRole
     * @return TypeRoleStructure
     */
    public function setTypeRole(\Application\Entity\Db\TypeRole $typeRole)
    {
        $this->typeRole = $typeRole;

        return $this;
    }

    /**
     * Get typeRole
     *
     * @return \Application\Entity\Db\TypeRole 
     */
    public function getTypeRole()
    {
        return $this->typeRole;
    }

    /**
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return TypeRoleStructure
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
}
