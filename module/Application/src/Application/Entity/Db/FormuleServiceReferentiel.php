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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
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
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant 
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }
}
