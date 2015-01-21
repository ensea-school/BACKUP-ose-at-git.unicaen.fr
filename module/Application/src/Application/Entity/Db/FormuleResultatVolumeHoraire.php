<?php

namespace Application\Entity\Db;

/**
 * FormuleResultatVolumeHoraire
 */
class FormuleResultatVolumeHoraire
{
    /**
     * @var float
     */
    private $serviceAssure;

    /**
     * @var float
     */
    private $heuresComplFa;

    /**
     * @var float
     */
    private $heuresComplFc;

    /**
     * @var float
     */
    private $heuresComplFi;

    /**
     * @var float
     */
    private $heuresService;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\VolumeHoraire
     */
    private $volumeHoraire;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;


    /**
     * Get serviceAssure
     *
     * @return float
     */
    public function getServiceAssure()
    {
        return $this->serviceAssure;
    }

    /**
     * Get heuresComplFa
     *
     * @return float 
     */
    public function getHeuresComplFa()
    {
        return $this->heuresComplFa;
    }

    /**
     * Get heuresComplFc
     *
     * @return float 
     */
    public function getHeuresComplFc()
    {
        return $this->heuresComplFc;
    }

    /**
     * Get heuresComplFi
     *
     * @return float 
     */
    public function getHeuresComplFi()
    {
        return $this->heuresComplFi;
    }

    /**
     * Get heuresService
     *
     * @return float 
     */
    public function getHeuresService()
    {
        return $this->heuresService;
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
     * Get volumeHoraire
     *
     * @return \Application\Entity\Db\VolumeHoraire 
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }

    /**
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat 
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }
}
