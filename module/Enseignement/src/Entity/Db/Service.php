<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Periode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enseignement\Entity\VolumeHoraireListe;
use Intervenant\Entity\Db\Intervenant;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\Etablissement;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\TypeIntervention;
use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Workflow\Entity\Db\Validation;

class Service implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, PlafondDataInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    protected ?int                $id                 = null;

    protected ?Intervenant        $intervenant        = null;

    protected ?TypeVolumeHoraire  $typeVolumeHoraire  = null;

    protected ?Etablissement      $etablissement      = null;

    protected ?ElementPedagogique $elementPedagogique = null;

    protected ?string             $description        = null;

    protected bool                $changed            = false;

    protected Collection          $volumeHoraire;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->volumeHoraire          = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function addVolumeHoraire(VolumeHoraire $volumeHoraire): Service
    {
        $this->volumeHoraire[] = $volumeHoraire;

        return $this;
    }



    public function removeVolumeHoraire(VolumeHoraire $volumeHoraire): Service
    {
        $this->volumeHoraire->removeElement($volumeHoraire);

        return $this;
    }



    /**
     * Get volumeHoraire
     *
     * @param Validation|null $validation
     *
     * @return Collection|VolumeHoraire[]
     */
    public function getVolumeHoraire(?Validation $validation = null): Collection
    {
        if ($validation) {
            $closure = function (VolumeHoraire $vh) use ($validation) {
                return $vh->getValidation()->contains($validation);
            };

            return $this->volumeHoraire->filter($closure);
        }

        return $this->volumeHoraire;
    }



    /**
     * @param Validation|null $validation
     *
     * @return array|VolumeHoraire[]
     */
    public function getOrderedVolumeHoraire(?Validation $validation = null): array
    {
        $vhs = $this->getVolumeHoraire($validation);
        $res = [];
        foreach ($vhs as $vh) {
            $res[] = $vh;
        }

        usort($res, function (VolumeHoraire $a, VolumeHoraire $b) {
            if ($a->getHoraireDebut() != $b->getHoraireDebut()) {
                $ahd = $a->getHoraireDebut() ? $a->getHoraireDebut()->getTimestamp() : 999999999999999999999999;
                $bhd = $b->getHoraireDebut() ? $b->getHoraireDebut()->getTimestamp() : 999999999999999999999999;

                return $ahd - $bhd;
            }

            if ($a->getHoraireFin() != $b->getHoraireFin()) {
                $ahf = $a->getHoraireFin() ? $a->getHoraireFin()->getTimestamp() : 999999999999999999999999;
                $bhf = $b->getHoraireFin() ? $b->getHoraireFin()->getTimestamp() : 999999999999999999999999;

                return $ahf - $bhf;
            }

            if ($a->getTypeIntervention() != $b->getTypeIntervention()) {
                $ati = $a->getTypeIntervention() ? $a->getTypeIntervention()->getOrdre() : 999999999999999999999999;
                $bti = $b->getTypeIntervention() ? $b->getTypeIntervention()->getOrdre() : 999999999999999999999999;

                return $ati - $bti;
            }
        });

        return array_values($res);
    }



    public function getVolumeHoraireListe(?Periode $periode = null, ?TypeIntervention $typeIntervention = null): VolumeHoraireListe
    {
        $volumeHoraireListe = new VolumeHoraireListe($this);
        if ($this->getTypeVolumeHoraire()) $volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        if ($periode) $volumeHoraireListe->setPeriode($periode);
        if ($typeIntervention) $volumeHoraireListe->setTypeIntervention($typeIntervention);

        return $volumeHoraireListe;
    }



    public function setIntervenant(?Intervenant $intervenant = null): Service
    {
        if ($this->intervenant !== $intervenant) {
            $this->intervenant = $intervenant;
            $this->changed     = true;
        }

        return $this;
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    public function setElementPedagogique(?ElementPedagogique $elementPedagogique = null): Service
    {
        if ($this->elementPedagogique !== $elementPedagogique) {
            $this->elementPedagogique = $elementPedagogique;
            $this->changed            = true;

            if ($elementPedagogique) {
                $vhl               = $this->getVolumeHoraireListe()->getVolumeHoraires();
                $typesIntervention = $elementPedagogique->getTypeIntervention();       // liste des types d'intervention de l'EP
                $periode           = $elementPedagogique->getPeriode();
                foreach ($vhl as $vh) {
                    if (
                        (!$typesIntervention->contains($vh->getTypeIntervention())) // types d'intervention devenus obsolètes
                        || ($periode && $vh->getPeriode() != $periode)               // périodes devenues obsolètes

                    ) {
                        $vh->setRemove(true); // Flag de demande de suppression du volume horaire lors de l'enregistrement de l'entité Service par son service Service
                    }
                }
            }
        }

        return $this;
    }



    public function getElementPedagogique(): ?ElementPedagogique
    {
        return $this->elementPedagogique;
    }



    public function setEtablissement(?Etablissement $etablissement = null): Service
    {
        if ($this->etablissement !== $etablissement) {
            $this->etablissement = $etablissement;
            $this->changed       = true;
        }

        return $this;
    }



    public function getEtablissement(): ?Etablissement
    {
        return $this->etablissement;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $description): Service
    {
        if ($this->description != $description) {
            $this->description = $description;
            $this->changed     = true;
        }

        return $this;
    }



    public function getStructure(): ?Structure
    {
        if ($this->getElementPedagogique()) {
            return $this->getElementPedagogique()->getStructure();
        }
        if ($this->getIntervenant()) {
            return $this->getIntervenant()->getStructure();
        }

        return null;
    }



    public function getTypeVolumeHoraire(): ?TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }



    public function setTypeVolumeHoraire(?TypeVolumeHoraire $typeVolumeHoraire): Service
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }



    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId(): string
    {
        return 'Service';
    }



    public function hasChanged(): bool
    {
        return $this->changed;
    }



    public function setChanged(bool $changed): Service
    {
        $this->changed = $changed;

        return $this;
    }

}
