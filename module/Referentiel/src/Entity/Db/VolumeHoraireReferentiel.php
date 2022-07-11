<?php

namespace Referentiel\Entity\Db;

use Application\Entity\Db\Traits\ServiceReferentielAwareTrait;
use Application\Entity\Db\Traits\TypeVolumeHoraireAwareTrait;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    use TypeVolumeHoraireAwareTrait;
    use ServiceReferentielAwareTrait;

    private ?int       $id;

    private ?float     $heures;

    private ?\DateTime $horaireDebut;

    private ?\DateTime $horaireFin;

    private bool       $autoValidation = false;

    private Collection $validation;

    private Collection $etatVolumeHoraireReferentiel;

    private Collection $formuleResultatVolumeHoraireReferentiel;

    private bool       $remove         = false;



    public function __construct()
    {
        $this->validation                              = new ArrayCollection();
        $this->etatVolumeHoraireReferentiel            = new ArrayCollection();
        $this->formuleResultatVolumeHoraireReferentiel = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getHeures(): ?float
    {
        return $this->heures;
    }



    public function setHeures(?float $heures): VolumeHoraireReferentiel
    {
        $this->heures = $heures;

        return $this;
    }



    public function getHoraireDebut(): ?\DateTime
    {
        return $this->horaireDebut;
    }



    public function setHoraireDebut(?\DateTime $horaireDebut): VolumeHoraireReferentiel
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }



    public function getHoraireFin(): ?\DateTime
    {
        return $this->horaireFin;
    }



    public function setHoraireFin(?\DateTime $horaireFin): VolumeHoraireReferentiel
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }



    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    public function setAutoValidation(bool $autoValidation): VolumeHoraireReferentiel
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    public function getRemove(): bool
    {
        return $this->remove;
    }



    public function setRemove(bool $remove): VolumeHoraireReferentiel
    {
        $this->remove = $remove;

        return $this;
    }



    public function addValidation(Validation $validation): VolumeHoraireReferentiel
    {
        $this->validation[] = $validation;

        return $this;
    }



    public function removeValidation(Validation $validation): VolumeHoraireReferentiel
    {
        $this->validation->removeElement($validation);

        return $this;
    }



    /**
     * @return Collection|Validation[]
     */
    public function getValidation(): Collection
    {
        return $this->validation;
    }



    public function isValide(): bool
    {
        if ($this->isAutoValidation()) return true;

        if ($validations = $this->getValidation()) {
            foreach ($validations as $validation) {
                if ($validation->estNonHistorise()) {
                    return true;
                }
            }
        }

        return false;
    }



    public function getEtatVolumeHoraireReferentiel(): Collection
    {
        return $this->etatVolumeHoraireReferentiel->first();
    }



    public function getFormuleResultatVolumeHoraireReferentiel(?TypeVolumeHoraire $typeVolumeHoraire = null, ?EtatVolumeHoraire $etatVolumeHoraire = null): Collection
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



    public function getUniqueFormuleResultatVolumeHoraireReferentiel(TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): ?FormuleResultatVolumeHoraireReferentiel
    {
        return $this->getFormuleResultatVolumeHoraireReferentiel($typeVolumeHoraire, $etatVolumeHoraire)->first() ?: null;
    }



    public function getResourceId(): string
    {
        return 'VolumeHoraireReferentiel';
    }
}
