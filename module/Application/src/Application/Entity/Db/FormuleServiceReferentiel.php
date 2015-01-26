<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleServiceReferentiel
 */
class FormuleServiceReferentiel
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleVolumeHoraireReferentiel;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\FormuleIntervenant
     */
    private $formuleIntervenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleVolumeHoraireReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleServiceReferentiel
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
     * Set serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return FormuleServiceReferentiel
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
     * Add formuleVolumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel
     * @return FormuleServiceReferentiel
     */
    public function addFormuleVolumeHoraireReferentiel(\Application\Entity\Db\FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel)
    {
        $this->formuleVolumeHoraireReferentiel[] = $formuleVolumeHoraireReferentiel;

        return $this;
    }

    /**
     * Remove formuleVolumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel
     */
    public function removeFormuleVolumeHoraireReferentiel(\Application\Entity\Db\FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel)
    {
        $this->formuleVolumeHoraireReferentiel->removeElement($formuleVolumeHoraireReferentiel);
    }

    /**
     * Get formuleVolumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleVolumeHoraireReferentiel()
    {
        return $this->formuleVolumeHoraireReferentiel;
    }

    public function getHeures(TypeVolumeHoraire $typeVolumeHoraire=null, EtatVolumeHoraire $etatVolumeHoraire=null)
    {
        $heures = 0;
        $vhs = $this->getFormuleVolumeHoraireReferentiel();
        foreach( $vhs as $vh ){
            /* @var $vh FormuleVolumeHoraire */
            $ok = true;
            if ($ok && $typeVolumeHoraire !== null && $vh->getTypeVolumeHoraire() !== $typeVolumeHoraire) $ok = false;
            if ($ok && $etatVolumeHoraire !== null && $vh->getEtatVolumeHoraire() !== $etatVolumeHoraire) $ok = false;
            if ($ok) $heures += $vh->getHeures ();
        }
        return $heures;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return FormuleServiceReferentiel
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
     * @return FormuleServiceReferentiel
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
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return FormuleServiceReferentiel
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set formuleIntervenant
     *
     * @param \Application\Entity\Db\FormuleIntervenant $formuleIntervenant
     * @return FormuleServiceReferentiel
     */
    public function setFormuleIntervenant(\Application\Entity\Db\FormuleIntervenant $formuleIntervenant = null)
    {
        $this->formuleIntervenant = $formuleIntervenant;

        return $this;
    }

    /**
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant 
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }
}
