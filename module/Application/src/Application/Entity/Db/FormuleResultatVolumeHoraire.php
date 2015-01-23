<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

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
    private $heuresService;

    /**
     * @var float
     */
    private $heuresComplFa;

    /**
     * @var float
     */
    private $heuresComplFi;

    /**
     * @var float
     */
    private $heuresComplFc;

    /**
     * @var float
     */
    private $heuresComplFcMajorees;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\VolumeHoraire
     */
    private $volumeHoraire;


    /**
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultatVolumeHoraire
     */
    public function setServiceAssure($serviceAssure)
    {
        $this->serviceAssure = $serviceAssure;

        return $this;
    }

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
     * Set heuresService
     *
     * @param float $heuresService
     * @return FormuleResultatVolumeHoraire
     */
    public function setHeuresService($heuresService)
    {
        $this->heuresService = $heuresService;

        return $this;
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
     * Set heuresComplFa
     *
     * @param float $heuresComplFa
     * @return FormuleResultatVolumeHoraire
     */
    public function setHeuresComplFa($heuresComplFa)
    {
        $this->heuresComplFa = $heuresComplFa;

        return $this;
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
     * Set heuresComplFi
     *
     * @param float $heuresComplFi
     * @return FormuleResultatVolumeHoraire
     */
    public function setHeuresComplFi($heuresComplFi)
    {
        $this->heuresComplFi = $heuresComplFi;

        return $this;
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
     * Set heuresComplFc
     *
     * @param float $heuresComplFc
     * @return FormuleResultatVolumeHoraire
     */
    public function setHeuresComplFc($heuresComplFc)
    {
        $this->heuresComplFc = $heuresComplFc;

        return $this;
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
     * Set heuresComplFcMajorees
     *
     * @param float $heuresComplFcMajorees
     * @return FormuleResultatVolumeHoraire
     */
    public function setHeuresComplFcMajorees($heuresComplFcMajorees)
    {
        $this->heuresComplFcMajorees = $heuresComplFcMajorees;

        return $this;
    }

    /**
     * Get heuresComplFcMajorees
     *
     * @return float 
     */
    public function getHeuresComplFcMajorees()
    {
        return $this->heuresComplFcMajorees;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleResultatVolumeHoraire
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set formuleResultat
     *
     * @param \Application\Entity\Db\FormuleResultat $formuleResultat
     * @return FormuleResultatVolumeHoraire
     */
    public function setFormuleResultat(\Application\Entity\Db\FormuleResultat $formuleResultat = null)
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
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

    /**
     * Set volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return FormuleResultatVolumeHoraire
     */
    public function setVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire = null)
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
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
}
