<?php

namespace Application\Entity\Db;

/**
 * VIndicDepassHcHorsRemuFc
 */
class VIndicDepassHcHorsRemuFc
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
     * @var Annee
     */
    protected $annee;
    
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
     * Get annee
     *
     * @return Annee 
     */
    function getAnnee()
    {
        return $this->annee;
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