<?php

namespace Intervenant\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Grade
 */
class Grade implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use CorpsAwareTrait;
    use ImportAwareTrait;

    protected ?int $id = null;

    protected ?string $libelleCourt = null;

    protected ?string $libelleLong = null;

    protected ?int $echelle = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setLibelleCourt(string $libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }



    public function setLibelleLong(string $libelleLong): self
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }



    public function getEchelle(): ?int
    {
        return $this->echelle;
    }



    public function setEchelle(int $echelle): self
    {
        $this->echelle = $echelle;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelleLong();
    }
}
