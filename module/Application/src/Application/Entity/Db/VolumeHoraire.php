<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * VolumeHoraire
 */
class VolumeHoraire implements HistoriqueAwareInterface, ResourceInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var float
     */
    protected $heures;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    protected $service;

    /**
     * @var \Application\Entity\Db\MotifNonPaiement
     */
    protected $motifNonPaiement;

    /**
     * @var \Application\Entity\Db\Periode
     */
    protected $periode;

    /**
     * @var \Application\Entity\Db\TypeIntervention
     */
    protected $typeIntervention;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    protected $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Contrat
     */
    protected $contrat;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $validation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $etatVolumeHoraire;

    /**
     * remove
     *
     * @var boolean
     */
    protected $remove = false;

    /**
     * @var FormuleVolumeHoraire
     */
    private $formuleVolumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatVolumeHoraire;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatVolumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        $ep = $this->getService()->getElementPedagogique();

        return implode(" - ", [
            "Id " . $this->getId(),
            $ep ? $ep->getStructure() : '',
            "Service " . $this->getService()->getId(),
            "EP " . ($ep ? $ep->getSourceCode() : '') . " (" . ($ep ? $ep->getId() : '') . ")",
            $this->getHeures() . "h",
            $this->getTypeIntervention(),
            count($this->getValidation()) . " validations",
            $this->getContrat() ? "Contrat " . $this->getContrat()->getId() : "Aucun contrat",
            $this->getHistoDestructeur() ? "Supprimé" : $this->getHistoModification()->format(\Common\Constants::DATETIME_FORMAT),
        ]);
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
     * @return VolumeHoraire
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     *
     * @return VolumeHoraire
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }



    /**
     * Get service
     *
     * @return \Application\Entity\Db\Service
     */
    public function getService()
    {
        return $this->service;
    }



    /**
     * Set motifNonPaiement
     *
     * @param \Application\Entity\Db\MotifNonPaiement $motifNonPaiement
     *
     * @return VolumeHoraire
     */
    public function setMotifNonPaiement(\Application\Entity\Db\MotifNonPaiement $motifNonPaiement = null)
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    /**
     * Get motifNonPaiement
     *
     * @return \Application\Entity\Db\MotifNonPaiement
     */
    public function getMotifNonPaiement()
    {
        return $this->motifNonPaiement;
    }



    /**
     * Set periode
     *
     * @param \Application\Entity\Db\Periode $periode
     *
     * @return VolumeHoraire
     */
    public function setPeriode(\Application\Entity\Db\Periode $periode = null)
    {
        $this->periode = $periode;

        return $this;
    }



    /**
     * Get periode
     *
     * @return \Application\Entity\Db\Periode
     */
    public function getPeriode()
    {
        return $this->periode;
    }



    /**
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     *
     * @return VolumeHoraire
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     *
     * @return VolumeHoraire
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
     * Set contrat
     *
     * @param \Application\Entity\Db\Contrat $contrat
     *
     * @return VolumeHoraire
     */
    public function setContrat(\Application\Entity\Db\Contrat $contrat = null)
    {
        $this->contrat = $contrat;

        return $this;
    }



    /**
     * Get contrat
     *
     * @return \Application\Entity\Db\Contrat
     */
    public function getContrat()
    {
        return $this->contrat;
    }



    /**
     * Add validation
     *
     * @param \Application\Entity\Db\Validation $validation
     *
     * @return self
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
     * @param \Application\Entity\Db\TypeValidation $type
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getValidation(TypeValidation $type = null)
    {
        if (null === $type) {
            return $this->validation;
        }
        if (null === $this->validation) {
            return null;
        }

        $filter      = function (Validation $validation) use ($type) {
            return $type === $validation->getTypeValidation();
        };
        $validations = $this->validation->filter($filter);

        return $validations;
    }



    /**
     * Get etatVolumeHoraire
     *
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire->first();
    }



    /**
     * Get formuleVolumeHoraire
     *
     * @return FormuleVolumeHoraire
     */
    public function getFormuleVolumeHoraire()
    {
        return $this->formuleVolumeHoraire;
    }



    /**
     * Get formuleResultatVolumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $filter = function (FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire) use ($typeVolumeHoraire, $etatVolumeHoraire) {
            if (isset($typeVolumeHoraire) && $typeVolumeHoraire !== $formuleResultatVolumeHoraire->getFormuleResultat()->getTypeVolumeHoraire()) {
                return false;
            }
            if (isset($etatVolumeHoraire) && $etatVolumeHoraire !== $formuleResultatVolumeHoraire->getFormuleResultat()->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultatVolumeHoraire->filter($filter);
    }



    /**
     * Get formuleResultatVolumeHoraire
     *
     * @return FormuleResultatVolumeHoraire
     */
    public function getUniqueFormuleResultatVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        return $this->getFormuleResultatVolumeHoraire($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }



    public function getResourceId()
    {
        return 'VolumeHoraire';
    }
}