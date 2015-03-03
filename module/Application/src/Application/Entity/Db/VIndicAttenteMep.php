<?php

namespace Application\Entity\Db;

/**
 * VIndicAttenteMep
 */
class VIndicAttenteMep
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
     * @var Structure
     */
    protected $structure;
    
    /**
     * @var float
     */
    protected $totalHeuresMep;

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
    function getTotalHeuresMep()
    {
        return $this->totalHeuresMep;
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
     * Get structure
     *
     * @return Structure 
     */
    function getStructure()
    {
        return $this->structure;
    }
}