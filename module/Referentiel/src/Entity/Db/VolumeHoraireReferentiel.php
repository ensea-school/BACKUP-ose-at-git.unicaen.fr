<?php

namespace Referentiel\Entity\Db;

use Contrat\Entity\Db\Contrat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Workflow\Entity\Db\Validation;

/**
 * VolumeHoraireReferentiel
 */
class VolumeHoraireReferentiel implements HistoriqueAwareInterface, ImportAwareInterface, ResourceInterface, PlafondDataInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ServiceReferentielAwareTrait;

    private ?int       $id             = null;

    private ?float     $heures         = null;

    private ?\DateTime $horaireDebut   = null;

    private ?\DateTime $horaireFin     = null;

    private bool       $autoValidation = false;

    private Collection $validation;

    private Collection $etatVolumeHoraireReferentiel;

    private bool       $remove         = false;

    private ?Contrat   $contrat        = null;



    public function __construct()
    {
        $this->validation                              = new ArrayCollection();
        $this->etatVolumeHoraireReferentiel            = new ArrayCollection();
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



    public function getEtatVolumeHoraireReferentiel(): EtatVolumeHoraire
    {
        return $this->etatVolumeHoraireReferentiel->first();
    }



    public function getResourceId(): string
    {
        return 'VolumeHoraireReferentiel';
    }



    public function setContrat(?Contrat $contrat = null): VolumeHoraireReferentiel
    {
        $this->contrat = $contrat;

        return $this;
    }



    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }

}
