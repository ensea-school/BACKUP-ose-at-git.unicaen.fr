<?php

namespace Application\Entity\Db;

use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * VolumeHoraireReferentiel
 */
class VolumeHoraireReferentiel implements HistoriqueAwareInterface, ImportAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $validation;

    /**
     * remove
     *
     * @var boolean
     */
    protected $remove = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etatVolumeHoraireReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatVolumeHoraireReferentiel;

    /**
     * @var boolean
     */
    private $autoValidation = false;

    /**
     * @var \DateTime
     */
    protected $horaireDebut;

    /**
     * @var \DateTime
     */
    protected $horaireFin;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->validation                              = new \Doctrine\Common\Collections\ArrayCollection();
        $this->etatVolumeHoraireReferentiel            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatVolumeHoraireReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * Détermine si le volume horaire a vocation à être supprimé ou non
     */
    public function setRemove($remove)
    {
        $this->remove = (boolean)$remove;

        return $this;
    }



    public function getRemove()
    {
        return $this->remove;
    }



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return VolumeHoraireReferentiel
     */
    public function setHeures($heures)
    {
        $this->heures = round($heures, 2);

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set typeVolumeHoraire
     *
     * @param TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return VolumeHoraireReferentiel
     */
    public function setTypeVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    /**
     * Get typeVolumeHoraire
     *
     * @return \Service\Entity\Db\TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }



    /**
     * Set serviceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     *
     * @return VolumeHoraireReferentiel
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
     * Add validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return VolumeHoraireReferentiel
     */
    public function addValidation(\Application\Entity\Db\Validation $validation)
    {
        $this->validation[] = $validation;

        return $this;
    }



    /**
     * Remove validation
     *
     * @param \Application\Entity\Db\Validation $validation
     */
    public function removeValidation(\Application\Entity\Db\Validation $validation)
    {
        $this->validation->removeElement($validation);
    }



    /**
     * Get validation
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValidation()
    {
        return $this->validation;
    }



    /**
     * Détermine si le VHR a une validation ou non
     *
     * @return boolean
     */
    public function hasValidation()
    {
        if ($this->isAutoValidation()) return true;

        $validations = $this->getValidation();
        foreach ($validations as $validation) {
            /* @var $validation Validation */
            if ($validation->estNonHistorise()) {
                return true;
            }
        }

        return false;
    }



    /**
     * Get etatVolumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEtatVolumeHoraireReferentiel()
    {
        return $this->etatVolumeHoraireReferentiel->first();
    }



    /**
     * Get formuleResultatVolumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatVolumeHoraireReferentiel(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $filter = function (FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel) use ($typeVolumeHoraire, $etatVolumeHoraire) {
            if (isset($typeVolumeHoraire) && $typeVolumeHoraire !== $formuleResultatVolumeHoraireReferentiel->getFormuleResultat()->getTypeVolumeHoraire()) {
                return false;
            }
            if (isset($etatVolumeHoraire) && $etatVolumeHoraire !== $formuleResultatVolumeHoraireReferentiel->getFormuleResultat()->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultatVolumeHoraireReferentiel->filter($filter);
    }



    /**
     * Get formuleResultatVolumeHoraireReferentiel
     *
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function getUniqueFormuleResultatVolumeHoraireReferentiel(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        return $this->getFormuleResultatVolumeHoraireReferentiel($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }



    /**
     * @return bool
     */
    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    /**
     * @param bool $autoValidation
     *
     * @return VolumeHoraire
     */
    public function setAutoValidation(bool $autoValidation): self
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    /**
     * Permet de savoir si ce volume horaire référentiel est validé ou non
     *
     * @return bool
     */
    public function isValide(): bool
    {
        if ($this->isAutoValidation()) return true;

        if ($validations = $this->getValidation()) {
            foreach ($validations as $validation) {
                if ($validation->estNonHistorise()) return true;
            }
        }

        return false;
    }



    /**
     * @return \DateTime
     */
    public function getHoraireDebut()
    {
        return $this->horaireDebut;
    }



    /**
     * @param \DateTime $horaireDebut
     *
     * @return VolumeHoraireReferentiel
     */
    public function setHoraireDebut($horaireDebut): VolumeHoraireReferentiel
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getHoraireFin()
    {
        return $this->horaireFin;
    }



    /**
     * @param \DateTime $horaireFin
     *
     * @return VolumeHoraireReferentiel
     */
    public function setHoraireFin($horaireFin): VolumeHoraireReferentiel
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }



    public function getResourceId()
    {
        return 'VolumeHoraireReferentiel';
    }
}
