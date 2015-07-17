<?php

namespace Application\Entity\Db;

class VIndicAttenteValidEnsAutre
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
}