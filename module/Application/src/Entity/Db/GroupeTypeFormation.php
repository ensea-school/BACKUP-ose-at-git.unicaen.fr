<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * GroupeTypeFormation
 */
class GroupeTypeFormation implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    protected ?string $libelleCourt = null;

    /**
     * @var string
     */
    protected ?string $libelleLong = null;

    /**
     * @var integer
     */
    protected ?int $ordre = null;

    /**
     * @var boolean
     */
    protected bool $pertinenceNiveau = true;

    /**
     * @var integer
     */
    protected ?int       $id = null;

    protected Collection $typeFormation;



    public function __construct()
    {
        $this->typeFormation = new ArrayCollection();
    }



    /**
     * Get PlafondReferentiel
     *
     * @return TypeFormation[]
     */
    public function getTypeFormation(): Collection
    {
        return $this->typeFormation;
    }



    /**
     * @return string
     */
    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    /**
     * @param string $libelleCourt
     */
    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }



    /**
     * @return string
     */
    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    /**
     * @param string $libelleLong
     */
    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }



    /**
     * @return int
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }



    /**
     * @param int $ordre
     */
    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }



    /**
     * @return bool
     */
    public function isPertinenceNiveau(): bool
    {
        return $this->pertinenceNiveau;
    }



    /**
     * @param bool $pertinenceNiveau
     */
    public function setPertinenceNiveau(bool $pertinenceNiveau): void
    {
        $this->pertinenceNiveau = $pertinenceNiveau;
    }



    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @param int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }



    public function __toString()
    {
        return $this->getLibelleLong();
    }

}
