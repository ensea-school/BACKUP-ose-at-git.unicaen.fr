<?php

namespace Application\Entity\Db;

/**
 * Indicateur
 */
class Indicateur
{
    const SAISIE_SERVICE_APRES_CONTRAT_AVENANT = 'SAISIE_SERVICE_APRES_CONTRAT_AVENANT';
    const ATTENTE_CONTRAT                      = 'ATTENTE_CONTRAT';
    const ATTENTE_AVENANT                      = 'ATTENTE_AVENANT';
    
    const TYPE_ALERTE = 'Alerte';
    const TYPE_INFO   = 'Info';

    /**
     * @var integer
     */
    private $id;
    
    /**
     * @var string
     */
    private $code;
    
    /**
     * @var boolean
     */
    private $enabled;
    
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $libelle;
    
    /**
     * @var integer
     */
    private $ordre;

    /**
     * @var boolean
     */
    private $structureDependant;
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
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
     * Set code
     *
     * @param string $code
     * @return Indicateur
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string 
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     * @return Indicateur
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean 
     */
    public function getEnabled()
    {
        return $this->enabled;
    }
    
    /**
     * Set type
     *
     * @param string $type
     * @return Indicateur
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     * @return Indicateur
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Indicateur
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }
}