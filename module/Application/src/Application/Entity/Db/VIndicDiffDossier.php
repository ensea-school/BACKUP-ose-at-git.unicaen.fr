<?php

namespace Application\Entity\Db;

/**
 * VIndicDiffDossier
 */
class VIndicDiffDossier
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
     * @var string
     */
    protected $adresseDossier;
    
    /**
     * @var string
     */
    protected $adresseImport;
    
    /**
     * @var string
     */
    protected $ribDossier;
    
    /**
     * @var string
     */
    protected $ribImport;
    
    /**
     * @var string
     */
    protected $nomUsuelDossier;
    
    /**
     * @var string
     */
    protected $nomUsuelImport;
    
    /**
     * @var string
     */
    protected $prenomDossier;
    
    /**
     * @var string
     */
    protected $prenomImport;

    function getId()
    {
        return $this->id;
    }

    function getAdresseDossier()
    {
        return $this->adresseDossier;
    }

    function getAdresseImport()
    {
        return $this->adresseImport;
    }

    function getRibDossier()
    {
        return $this->ribDossier;
    }

    function getRibImport()
    {
        return $this->ribImport;
    }

    function getNomUsuelDossier()
    {
        return $this->nomUsuelDossier;
    }

    function getNomUsuelImport()
    {
        return $this->nomUsuelImport;
    }

    function getPrenomDossier()
    {
        return $this->prenomDossier;
    }

    function getPrenomImport()
    {
        return $this->prenomImport;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}
