<?php

namespace Mission\Entity\Db;

use Doctrine\Common\Collections\Collection;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Lieu\Entity\Db\StructureAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenVue\Axios\AxiosExtractorInterface;
use Workflow\Entity\Db\Validation;

class OffreEmploi implements HistoriqueAwareInterface, ResourceInterface, AxiosExtractorInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    protected ?int         $id             = null;

    protected ?TypeMission $typeMission    = null;

    protected ?\DateTime   $dateDebut      = null;

    protected ?\DateTime   $dateFin        = null;

    protected ?\DateTime   $dateLimite     = null;

    protected ?string      $titre          = null;

    protected ?string      $description    = null;

    protected ?Structure   $structure      = null;

    protected ?int         $nombreHeures   = null;

    protected ?int         $nombrePostes   = null;

    protected bool         $autoValidation = false;

    protected Collection   $candidatures;

    protected ?Validation  $validation     = null;



    public function __construct ()
    {
    }



    public function axiosDefinition (): array
    {
        return [
            'typeMission',
            'dateDebut',
            'dateFin',
            'dateLimite',
            'structure',
            'titre',
            'description',
            'nombreHeures',
            'nombrePostes',
            'histoCreation',
            'histoCreateur',
            'validation',
            ['candidatures', ['intervenant']],

        ];
    }



    public function getResourceId ()
    {
        return 'OffreEmploi';
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString ()
    {
        return 'Offre emploi ' . $this->getId();
    }



    public function getId (): ?int
    {
        return $this->id;
    }



    public function getTypeMission (): ?TypeMission
    {
        return $this->typeMission;
    }



    public function setTypeMission (?TypeMission $typeMission): self
    {
        $this->typeMission = $typeMission;

        return $this;
    }



    public function getDateDebut (): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut (?\DateTime $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    public function getDateFin (): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin (?\DateTime $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    public function getDateLimite (): ?\DateTime
    {
        return $this->dateLimite;
    }



    public function setDateLimite (?\DateTime $dateLimite): self
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }



    public function getTitre (): ?string
    {
        return $this->titre;
    }



    public function setTitre (?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }



    public function getDescription (): ?string
    {
        return $this->description;
    }



    public function setDescription (?string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getStructure (): ?Structure
    {
        return $this->structure;
    }



    public function setStructure (?Structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }



    public function getNombreHeures (): ?int
    {
        return $this->nombreHeures;
    }



    public function setNombreHeures (?int $nombreHeures): self
    {
        $this->nombreHeures = $nombreHeures;

        return $this;
    }



    public function getNombrePostes (): ?int
    {
        return $this->nombrePostes;
    }



    public function setNombrePostes (?int $nombrePostes): self
    {
        $this->nombrePostes = $nombrePostes;

        return $this;
    }



    /**
     * @return Collection|Candidature[]
     */
    public function getCandidatures (): Collection
    {
        return $this->candidatures;
    }



    public function addCandidature (Candidature $candidature): self
    {
        $this->candidatures[] = $candidature;

        return $this;
    }



    public function removeCandidature (Candidature $candidature): self
    {
        $this->candidatures->removeElement($candidature);

        return $this;
    }



    public function canSaisie (): bool
    {
        if ($this->isValide()) {
            return false;
        }

        return true;
    }



    public function isValide (): bool
    {
        if ($this->isAutoValidation()) return true;

        if ($validation = $this->getValidation()) {
            if ($validation->estNonHistorise()) return true;
        }

        return false;
    }



    /**
     * @return bool
     */
    public function isAutoValidation (): bool
    {
        return $this->autoValidation;
    }



    /**
     * @param bool $autoValidation
     *
     * @return Mission
     */
    public function setAutoValidation (bool $autoValidation): OffreEmploi
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    /**
     * @return Validation
     */
    public function getValidation (): ?Validation
    {
        return $this->validation;
    }



    /**
     * @param Validation $validation
     *
     * @return OffreEmploi
     */
    public function setValidation (?Validation $validation): OffreEmploi
    {
        $this->validation = $validation;

        return $this;
    }



    public function canSupprime (): bool
    {
        if ($this->isValide() || !empty($this->candidatures)) {
            return false;
        }


        return true;
    }



    public function isCandidat (Intervenant $intervenant): bool
    {
        foreach ($this->candidatures as $candidature) {
            if ($candidature->getIntervenant() == $intervenant) {
                return true;
            }
        }

        return false;
    }



    public function haveCandidats (): bool
    {

        if (!empty($this->candidatures)) {
            return true;
        }

        return false;
    }



    public function getCandidaturesValides (): Collection
    {
        return $this->candidatures->filter(function (Candidature $c) {
            return $c->isValide();
        });
    }



    public function getCandidats (): array
    {
        $idsIntervenant = [];
        /**
         * @var Candidature $candidature
         */
        foreach ($this->candidatures as $candidature) {
            if ($candidature->estNonHistorise()) {
                $idsIntervenant[] = $candidature->getIntervenant()->getId();
            }
        }

        return $idsIntervenant;
    }

}
