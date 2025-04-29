<?php

namespace Enseignement\Entity\Db;

use Application\Entity\Db\Traits\PeriodeAwareTrait;
use Contrat\Entity\Db\Contrat;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use Paiement\Entity\Db\MotifNonPaiementAwareTrait;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\Traits\TagAwareTrait;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Workflow\Entity\Db\TypeValidation;
use Workflow\Entity\Db\Validation;

class VolumeHoraire implements HistoriqueAwareInterface, ResourceInterface, ImportAwareInterface, PlafondDataInterface, PlafondPerimetreInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use ServiceAwareTrait;
    use MotifNonPaiementAwareTrait;
    use TagAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeInterventionAwareTrait;
    use PeriodeAwareTrait;

    protected ?int       $id             = null;

    protected ?float     $heures         = null;

    protected ?\DateTime $horaireDebut   = null;

    protected ?\DateTime $horaireFin     = null;

    protected ?Contrat   $contrat        = null;

    protected bool       $autoValidation = false;

    protected bool       $remove         = false;

    private Collection   $validation;

    private Collection   $etatVolumeHoraire;




    /**
     * Constructor
     */
    public function __construct()
    {
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

        $result = $this->etatVolumeHoraire->first();

        return ($result) ?: null;
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