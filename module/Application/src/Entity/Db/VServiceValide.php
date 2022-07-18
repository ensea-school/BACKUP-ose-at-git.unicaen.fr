<?php

namespace Application\Entity\Db;

/**
 * VServiceValide
 */
class VServiceValide
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var \Application\Entity\Db\ElementPedagogique
     */
    protected $elementPedagogique;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    protected $intervenant;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var \Enseignement\Entity\Db\Service
     */
    protected $service;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Enseignement\Entity\Db\VolumeHoraire
     */
    protected $volumeHoraire;

    /**
     * @var integer
     */
    private $id;



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
     * Get elementPedagogiqueId
     *
     * @return integer
     */
    public function getElementPedagogiqueId()
    {
        return $this->elementPedagogiqueId;
    }



    /**
     * Get heures
     *
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
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
     * Get service
     *
     * @return \Enseignement\Entity\Db\Service
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Get volumeHoraire
     *
     * @return \Enseignement\Entity\Db\VolumeHoraire
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
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
}
