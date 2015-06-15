<?php

namespace Application\Entity\Db;

/**
 * VIndicAttenteValidationServiceRef
 */
class VIndicAttenteValidationServiceRef
{
    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var Intervenant
     */
    protected $intervenant;
    
    /**
     * @var Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getIntervenant();
    }
    
    /**
     * 
     * @return int
     */
    function getId()
    {
        return $this->id;
    }

    /**
     * Get intervenant
     *
     * @return Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
    
    /**
     * Get structure
     *
     * @return Structure 
     */
    function getStructure()
    {
        return $this->structure;
    }

    /**
     * Get typeVolumeHoraire
     *
     * @return TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }
}