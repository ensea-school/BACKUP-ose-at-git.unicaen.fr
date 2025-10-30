<?php

namespace Workflow\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Structure;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenVue\Axios\AxiosExtractorInterface;

class Validation implements HistoriqueAwareInterface, ResourceInterface, AxiosExtractorInterface
{
    use HistoriqueAwareTrait;


    private ?int            $id             = null;
    private ?Intervenant    $intervenant    = null;
    private ?Structure      $structure      = null;
    private ?TypeValidation $typeValidation = null;
    private Collection      $volumeHoraire;
    private Collection      $volumeHoraireReferentiel;



    public function __construct()
    {
        $this->volumeHoraire            = new ArrayCollection();
        $this->volumeHoraireReferentiel = new ArrayCollection();
    }



    public function __toString(): string
    {
        return sprintf("Validation du %s par %s",
                       $this->getHistoCreation()->format(\Application\Constants::DATETIME_FORMAT),
                       $this->getHistoCreateur());
    }



    public function axiosDefinition(): array
    {
        return ['histoCreation', 'histoCreateur'];
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setIntervenant(?Intervenant $intervenant = null): self
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    public function setStructure(?Structure $structure = null): self
    {
        $this->structure = $structure;

        return $this;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setTypeValidation(TypeValidation $typeValidation): self
    {
        $this->typeValidation = $typeValidation;

        return $this;
    }



    public function getTypeValidation(): TypeValidation
    {
        return $this->typeValidation;
    }



    public function addVolumeHoraire(VolumeHoraire $volumeHoraire): self
    {
        $this->volumeHoraire[] = $volumeHoraire;

        return $this;
    }



    public function removeVolumeHoraire(VolumeHoraire $volumeHoraire): void
    {
        $this->volumeHoraire->removeElement($volumeHoraire);
    }



    /**
     * @return Collection|VolumeHoraire[]
     */
    public function getVolumeHoraire(): Collection|array
    {
        return $this->volumeHoraire;
    }



    public function addVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel): self
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }



    public function removeVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel): void
    {
        $this->volumeHoraireReferentiel->removeElement($volumeHoraireReferentiel);
    }



    /**
     * @return Collection|VolumeHoraireReferentiel[]
     */
    public function getVolumeHoraireReferentiel(): Collection|array
    {
        return $this->volumeHoraireReferentiel;
    }



    public function getResourceId(): string
    {
        return self::class;
    }
}
