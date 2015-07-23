<?php

namespace Application\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * TypeIntervention
 */
class TypeIntervention implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const CODE_CM = 'CM';
    const CODE_TD = 'TD';
    const CODE_TP = 'TP';

    /**
     * @var string
     */
    protected $code;

    /**
     * @var boolean
     */
    protected $interventionIndividualisee;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var integer
     */
    protected $ordre;

    /**
     * @var integer
     */
    protected $id;

    /**
     *
     * @var float
     */
    protected $tauxHetdService;

    /**
     *
     * @var float
     */
    protected $tauxHetdComplementaire;

    /**
     * visible
     *
     * @var boolean
     */
    protected $visible;



    public function __toString()
    {
        return (string)$this->getCode();
    }



    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeIntervention
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



    public function getVisible()
    {
        return $this->visible;
    }



    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }



    /**
     * Set interventionIndividualisee
     *
     * @param boolean $interventionIndividualisee
     *
     * @return TypeIntervention
     */
    public function setInterventionIndividualisee($interventionIndividualisee)
    {
        $this->interventionIndividualisee = $interventionIndividualisee;

        return $this;
    }



    /**
     * Get interventionIndividualisee
     *
     * @return boolean
     */
    public function getInterventionIndividualisee()
    {
        return $this->interventionIndividualisee;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeIntervention
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
     *
     * @return TypeIntervention
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



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    public function getTauxHetdService()
    {
        return $this->tauxHetdService;
    }



    public function getTauxHetdComplementaire()
    {
        return $this->tauxHetdComplementaire;
    }



    public function setTauxHetdService($tauxHetdService)
    {
        $this->tauxHetdService = $tauxHetdService;

        return $this;
    }



    public function setTauxHetdComplementaire($tauxHetdComplementaire)
    {
        $this->tauxHetdComplementaire = $tauxHetdComplementaire;

        return $this;
    }

}
