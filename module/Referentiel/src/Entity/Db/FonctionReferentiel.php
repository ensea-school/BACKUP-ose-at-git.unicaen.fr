<?php

namespace Referentiel\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Interfaces\ParametreEntityInterface;
use Administration\Traits\ChampsAutresTrait;
use Administration\Traits\ParametreEntityTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityNotFoundException;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use Paiement\Entity\Db\DomaineFonctionnelAwareTrait;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FonctionReferentiel implements ParametreEntityInterface, PlafondPerimetreInterface, PlafondDataInterface, ResourceInterface, ChampsAutresInterface
{
    use ParametreEntityTrait;
    use HistoriqueAwareTrait;
    use StructureAwareTrait;
    use DomaineFonctionnelAwareTrait;
    use ChampsAutresTrait;

    protected ?FonctionReferentiel $parent            = null;

    protected ?string              $code              = null;

    protected ?string              $libelleCourt      = null;

    protected ?string              $libelleLong       = null;

    protected ?int                 $id                = null;

    protected bool                 $etapeRequise      = false;

    protected bool                 $serviceStatutaire = true;

    /**
     * @var FonctionReferentiel[]
     */
    protected Collection $fille;



    public function __construct()
    {
        $this->fille = new ArrayCollection();
    }



    public function getResourceId(): string
    {
        return self::class;
    }



    public function getParent(): ?FonctionReferentiel
    {
        return $this->parent;
    }



    public function setParent(?FonctionReferentiel $parent): FonctionReferentiel
    {
        $this->parent = $parent;

        return $this;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): FonctionReferentiel
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    public function setLibelleLong(?string $libelleLong): FonctionReferentiel
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): FonctionReferentiel
    {
        $this->id = $id;

        return $this;
    }



    public function isEtapeRequise(): bool
    {
        return $this->etapeRequise;
    }



    public function setEtapeRequise(bool $etapeRequise): FonctionReferentiel
    {
        $this->etapeRequise = $etapeRequise;

        return $this;
    }



    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    public function setServiceStatutaire(bool $serviceStatutaire): FonctionReferentiel
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    public function addFille(FonctionReferentiel $fille): FonctionReferentiel
    {
        $this->fille[] = $fille;

        return $this;
    }



    public function removeFille(FonctionReferentiel $fille): FonctionReferentiel
    {
        $this->fille->removeElement($fille);

        return $this;
    }



    /**
     * @return ArrayCollection|FonctionReferentiel[]
     */
    public function getFille(): Collection
    {
        return $this->fille;
    }



    public function __toString(): string
    {
        $str = $this->getLibelleCourt();
        //Try catch préventif dans le cas d'une fonction référentiel attachée à une structure historisée.
        try {
            if ($this->getStructure()) {
                $str .= " (" . $this->getStructure() . ")";
            }
        } catch (EntityNotFoundException $e) {
            return $str;
        }

        return $str;
    }



    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    public function setLibelleCourt(?string $libelleCourt): FonctionReferentiel
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }
}
