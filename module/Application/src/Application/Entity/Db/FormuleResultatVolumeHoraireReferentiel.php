<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleResultatVolumeHoraireReferentiel
 */
class FormuleResultatVolumeHoraireReferentiel
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
    private $heuresComplReferentiel;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\VolumeHoraireReferentiel
     */
    private $volumeHoraireReferentiel;


    /**
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultatVolumeHoraireReferentiel
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
     * @return FormuleResultatVolumeHoraireReferentiel
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
     * Set heuresComplReferentiel
     *
     * @param float $heuresComplReferentiel
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function setHeuresComplReferentiel($heuresComplReferentiel)
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;

        return $this;
    }

    /**
     * Get heuresComplReferentiel
     *
     * @return float 
     */
    public function getHeuresComplReferentiel()
    {
        return $this->heuresComplReferentiel;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleResultatVolumeHoraireReferentiel
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
     * @return FormuleResultatVolumeHoraireReferentiel
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
     * Set volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function setVolumeHoraireReferentiel(\Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel = null)
    {
        $this->volumeHoraireReferentiel = $volumeHoraireReferentiel;

        return $this;
    }

    /**
     * Get volumeHoraireReferentiel
     *
     * @return \Application\Entity\Db\VolumeHoraireReferentiel 
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }
}
