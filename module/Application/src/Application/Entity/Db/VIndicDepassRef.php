<?php

namespace Application\Entity\Db;

/**
 * VIndicDepassRef
 */
class VIndicDepassRef
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
     * @var TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;
    
    /**
     * @var Structure
     */
    protected $structure;
    
    /**
     * @var float
     */
    protected $total;
    
    /**
     * @var float
     */
    protected $plafond;

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
     * 
     * @return float
     */
    function getTotal()
    {
        return $this->total;
    }

    /**
     * 
     * @return float
     */
    function getPlafond()
    {
        return $this->plafond;
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
     * Get TypeVolumeHoraire
     *
     * @return TypeVolumeHoraire 
     */
    function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
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
}