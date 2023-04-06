<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Validation;
use Application\Interfaces\AxiosExtractor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\TauxRemu;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class OffreEmploi implements HistoriqueAwareInterface, ResourceInterface, AxiosExtractor
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    protected ?int         $id             = null;

    protected ?TypeMission $typeMission    = null;

    protected ?\DateTime   $dateDebut      = null;

    protected ?\DateTime   $dateFin        = null;

    protected ?string      $titre          = null;

    protected ?string      $description    = null;

    protected ?int         $nombreHeures   = null;

    protected ?int         $nombrePostes   = null;

    protected bool         $autoValidation = false;

    private Collection     $etudiants;

    private ?Validation    $validation     = null;



    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
    }



    public function axiosDefinition(): array
    {
        return [
            'typeMission',
            'dateDebut',
            'dateFin',
            'structure',
            'titre',
            'description',
            'nombreHeures',
            'nombrePostes',
            'histoCreation',
            'histoCreateur',
            'validation',
            'etudiants',

        ];
    }



    public function getResourceId()
    {
        return 'OffreEmploi';
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getTypeMission(): ?TypeMission
    {
        return $this->typeMission;
    }



    public function setTypeMission(?TypeMission $typeMission): self
    {
        $this->typeMission = $typeMission;

        return $this;
    }



    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut(?\DateTime $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin(?\DateTime $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    public function getTitre(): ?string
    {
        return $this->titre;
    }



    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getNombreHeures(): ?int
    {
        return $this->nombreHeures;
    }



    public function setNombreHeures(?int $nombreHeures): self
    {
        $this->nombreHeures = $nombreHeures;

        return $this;
    }



    public function getNombrePostes(): ?int
    {
        return $this->nombrePostes;
    }



    public function setNombrePostes(?int $nombrePostes): self
    {
        $this->nombrePostes = $nombrePostes;

        return $this;
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
     * @return Mission
     */
    public function setAutoValidation(bool $autoValidation): OffreEmploi
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    /**
     * @return Collection|Intervenant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }



    public function addEtudiant(Intervenant $intervenant): self
    {
        $this->etudiants[] = $intervenant;

        return $this;
    }



    public function removeEtudiant(Intervenant $intervenant): self
    {
        $this->etudiants->removeElement($intervenant);

        return $this;
    }



    /**
     * @return Validation
     */
    public function getValidation(): ?Validation
    {
        return $this->validation;
    }



    /**
     * @param Validation $validation
     *
     * @return OffreEmploi
     */
    public function setValidation(?Validation $validation): OffreEmploi
    {
        $this->validation = $validation;

        return $this;
    }



    public function isValide(): bool
    {
        if ($this->isAutoValidation()) return true;

        if ($validation = $this->getValidation()) {
            if ($validation->estNonHistorise()) return true;
        }

        return false;
    }

}
