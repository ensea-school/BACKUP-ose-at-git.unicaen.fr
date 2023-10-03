<?php

namespace Referentiel\Entity\Db;

use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Service\Entity\Db\Traits\TagAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Paiement\Entity\Db\MotifNonPaiementAwareTrait;
use Plafond\Interfaces\PlafondDataInterface;
use Referentiel\Entity\VolumeHoraireReferentielListe;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Util;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * ServiceReferentiel
 */
class ServiceReferentiel implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, PlafondDataInterface
{
    use FonctionReferentielAwareTrait;
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ImportAwareTrait;
    use TagAwareTrait;
    use MotifNonPaiementAwareTrait;

    protected ?int $id = null;

    protected ?string $commentaires = null;

    protected ?string $formation = null;

    private Collection $volumeHoraireReferentiel;

    private Collection $formuleResultatServiceReferentiel;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }


    public function setCommentaires(?string $commentaires): ServiceReferentiel
    {
        $this->commentaires = $commentaires;

        return $this;
    }


    public function getFormation(): ?string
    {
        return $this->formation;
    }


    public function setFormation(?string $formation): ServiceReferentiel
    {
        $this->formation = $formation;

        return $this;
    }


    public function __toString(): string
    {
        return sprintf("%s : %s",
            $this->getStructure() ? " - " . $this->getStructure() : null,
            $this->getFonctionReferentiel());
    }


    public function __construct()
    {
        $this->volumeHoraireReferentiel = new ArrayCollection();
        $this->formuleResultatServiceReferentiel = new ArrayCollection();
    }


    public function addVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel): ServiceReferentiel
    {
        $this->volumeHoraireReferentiel[] = $volumeHoraireReferentiel;

        return $this;
    }


    public function removeVolumeHoraireReferentiel(VolumeHoraireReferentiel $volumeHoraireReferentiel): ServiceReferentiel
    {
        $this->volumeHoraireReferentiel->removeElement($volumeHoraireReferentiel);

        return $this;
    }


    /**
     * Get volumeHoraireReferentiel
     *
     * @return Collection|VolumeHoraireReferentiel[]
     */
    public function getVolumeHoraireReferentiel(): Collection
    {
        return $this->volumeHoraireReferentiel;
    }


    /**
     * Détermine si le VHR a une validation ou non
     *
     * @return bool
     */
    public function hasValidation(): bool
    {
        $volumesHoraires = $this->getVolumeHoraireReferentiel();
        foreach ($volumesHoraires as $volumeHoraire) {
            /* @var $volumeHoraire VolumeHoraireReferentiel */
            if ($volumeHoraire->isValide()) {
                return true;
            }
        }

        return false;
    }


    public function getVolumeHoraireReferentielListe(): VolumeHoraireReferentielListe
    {
        $volumeHoraireListe = new VolumeHoraireReferentielListe($this);

        if ($this->getTypeVolumeHoraire()) {
            $volumeHoraireListe->setTypeVolumeHoraire($this->getTypeVolumeHoraire());
        }

        return $volumeHoraireListe;
    }


    public function getFormuleResultatServiceReferentiel(TypeVolumeHoraire $typeVolumeHoraire = null, EtatVolumeHoraire $etatVolumeHoraire = null): Collection
    {
        $filter = function (FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel) use ($typeVolumeHoraire, $etatVolumeHoraire) {
            if (isset($typeVolumeHoraire) && $typeVolumeHoraire !== $formuleResultatServiceReferentiel->getFormuleResultat()->getTypeVolumeHoraire()) {
                return false;
            }
            if (isset($etatVolumeHoraire) && $etatVolumeHoraire !== $formuleResultatServiceReferentiel->getFormuleResultat()->getEtatVolumeHoraire()) {
                return false;
            }

            return true;
        };

        return $this->formuleResultatServiceReferentiel->filter($filter);
    }


    public function getUniqueFormuleResultatServiceReferentiel(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): FormuleResultatServiceReferentiel
    {
        return $this->getFormuleResultatServiceReferentiel($typeVolumeHoraire, $etatVolumeHoraire)->first();
    }


    public function getResourceId(): string
    {
        return 'ServiceReferentiel';
    }
}
