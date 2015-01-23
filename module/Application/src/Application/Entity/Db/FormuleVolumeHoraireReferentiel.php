<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleVolumeHoraireReferentiel
 */
class FormuleVolumeHoraireReferentiel
{
    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\VolumeHoraireReferentiel
     */
    private $volumeHoraireReferentiel;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\EtatVolumeHoraire
     */
    private $etatVolumeHoraire;

    /**
     * @var \Application\Entity\Db\FormuleServiceReferentiel
     */
    private $formuleServiceReferentiel;


    /**
     * Set heures
     *
     * @param float $heures
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
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
     * Set id
     *
     * @param integer $id
     * @return FormuleVolumeHoraireReferentiel
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
     * Set volumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\VolumeHoraireReferentiel $volumeHoraireReferentiel
     * @return FormuleVolumeHoraireReferentiel
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

    /**
     * Set serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel = null)
    {
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }

    /**
     * Get serviceReferentiel
     *
     * @return \Application\Entity\Db\ServiceReferentiel 
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee 
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setTypeVolumeHoraire(\Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }

    /**
     * Get typeVolumeHoraire
     *
     * @return \Application\Entity\Db\TypeVolumeHoraire 
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }

    /**
     * Set etatVolumeHoraire
     *
     * @param \Application\Entity\Db\EtatVolumeHoraire $etatVolumeHoraire
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setEtatVolumeHoraire(\Application\Entity\Db\EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }

    /**
     * Get etatVolumeHoraire
     *
     * @return \Application\Entity\Db\EtatVolumeHoraire 
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }

    /**
     * Set formuleServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel
     * @return FormuleVolumeHoraireReferentiel
     */
    public function setFormuleServiceReferentiel(\Application\Entity\Db\FormuleServiceReferentiel $formuleServiceReferentiel = null)
    {
        $this->formuleServiceReferentiel = $formuleServiceReferentiel;

        return $this;
    }

    /**
     * Get formuleServiceReferentiel
     *
     * @return \Application\Entity\Db\FormuleServiceReferentiel 
     */
    public function getFormuleServiceReferentiel()
    {
        return $this->formuleServiceReferentiel;
    }
}
