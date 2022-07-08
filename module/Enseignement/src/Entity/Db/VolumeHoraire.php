<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Contrat;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\FormuleResultatVolumeHoraire;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

/**
 * VolumeHoraire
 */
class VolumeHoraire implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    protected ?int               $id;

    protected ?Service           $service;

    protected ?TypeVolumeHoraire $typeVolumeHoraire;

    protected ?TypeIntervention  $typeIntervention;

    protected ?Periode           $periode;

    protected ?float             $heures;

    protected ?\DateTime         $horaireDebut;

    protected ?\DateTime         $horaireFin;

    protected ?MotifNonPaiement  $motifNonPaiement;

    protected ?Contrat           $contrat;

    protected bool               $autoValidation = false;

    protected bool               $remove         = false;

    private Collection           $validation;

    private Collection           $etatVolumeHoraire;

    private Collection           $formuleResultatVolumeHoraire;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatVolumeHoraire = new ArrayCollection();
        $this->validation                   = new ArrayCollection();
        $this->etatVolumeHoraire            = new ArrayCollection();
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
            "EP " . ($ep ? $ep->getCode() : '') . " (" . ($ep ? $ep->getId() : '') . ")",
            $this->getHeures() . "h",
            $this->getTypeIntervention(),
            count($this->getValidation()) . " validations",
            $this->getContrat() ? "Contrat " . $this->getContrat()->getId() : "Aucun contrat",
            $this->getHistoDestructeur() ? "Supprimé" : $this->getHistoModification()->format(\Application\Constants::DATETIME_FORMAT),
        ]);
    }



    public function setRemove($remove): VolumeHoraire
    {
        $this->remove = (boolean)$remove;

        return $this;
    }



    public function getRemove(): bool
    {
        return $this->remove;
    }



    public function setHeures(?float $heures): VolumeHoraire
    {
        $this->heures = round($heures, 2);

        return $this;
    }



    public function getHeures(): ?float
    {
        return $this->heures;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(int $id)
    {
        $this->id = $id;
    }



    public function setService(Service $service = null): VolumeHoraire
    {
        $this->service = $service;

        return $this;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }



    public function setMotifNonPaiement(?MotifNonPaiement $motifNonPaiement = null): VolumeHoraire
    {
        $this->motifNonPaiement = $motifNonPaiement;

        return $this;
    }



    public function getMotifNonPaiement(): ?MotifNonPaiement
    {
        return $this->motifNonPaiement;
    }



    public function setPeriode(Periode $periode = null): VolumeHoraire
    {
        $this->periode = $periode;

        return $this;
    }



    public function getPeriode(): ?Periode
    {
        return $this->periode;
    }



    public function setTypeIntervention(?TypeIntervention $typeIntervention = null): VolumeHoraire
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    public function getTypeIntervention(): ?TypeIntervention
    {
        return $this->typeIntervention;
    }



    public function setTypeVolumeHoraire(?TypeVolumeHoraire $typeVolumeHoraire = null): VolumeHoraire
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    public function getTypeVolumeHoraire(): TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }



    public function setContrat(?Contrat $contrat = null): VolumeHoraire
    {
        $this->contrat = $contrat;

        return $this;
    }



    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }



    public function addValidation(Validation $validation): VolumeHoraire
    {
        $this->validation[] = $validation;

        return $this;
    }



    public function removeValidation(Validation $validation): VolumeHoraire
    {
        $this->validation->removeElement($validation);

        return $this;
    }



    /**
     * Get validation
     *
     * @param TypeValidation|null $type
     *
     * @return Collection|Validation[]|null
     */
    public function getValidation(?TypeValidation $type = null): ?Collection
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



    public function getEtatVolumeHoraire(): ?EtatVolumeHoraire
    {
        if (!$this->etatVolumeHoraire) return null;

        return $this->etatVolumeHoraire->first();
    }



    /**
     * Get formuleResultatVolumeHoraire
     *
     * @return Collection|FormuleResultatVolumeHoraire[]
     */
    public function getFormuleResultatVolumeHoraire(?TypeVolumeHoraire $typeVolumeHoraire = null, ?EtatVolumeHoraire $etatVolumeHoraire = null): Collection
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



    public function getUniqueFormuleResultatVolumeHoraire(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): ?FormuleResultatVolumeHoraire
    {
        return $this->getFormuleResultatVolumeHoraire($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }



    public function getResourceId(): string
    {
        return 'VolumeHoraire';
    }



    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



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



    public function setAutoValidation(bool $autoValidation): VolumeHoraire
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    public function getHoraireDebut(): ?\DateTime
    {
        return $this->horaireDebut;
    }



    public function setHoraireDebut(?\DateTime $horaireDebut): VolumeHoraire
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }



    public function getHoraireFin(): ?\DateTime
    {
        return $this->horaireFin;
    }



    public function setHoraireFin(?\DateTime $horaireFin): VolumeHoraire
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }
}