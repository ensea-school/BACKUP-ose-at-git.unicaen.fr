<?php

namespace Application\Entity\Db;

use Laminas\Validator\Date;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Tag implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    protected ?int     $id           = null;

    protected ?string  $code         = null;

    protected ?string  $libelleCourt = null;

    protected ?string  $libelleLong  = null;

    private ?\DateTime $dateDebut    = null;

    private ?\DateTime $dateFin      = null;



    public function getId (): ?int
    {
        return $this->id;
    }



    public function getCode (): ?string
    {
        return $this->code;
    }



    public function setCode (?string $code): Tag
    {
        $this->code = $code;

        return $this;
    }



    public function __toString (): string
    {
        return $this->getLibelleLong() ? : $this->getLibelleCourt();
    }



    public function getLibelleLong (): ?string
    {
        return $this->libelleLong;
    }



    public function setLibelleLong (?string $libelleLong): Tag
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }



    public function getLibelleCourt (): ?string
    {
        return $this->libelleCourt;
    }



    public function setLibelleCourt (?string $libelleCourt): Tag
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }



    public function getDateDebut (): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut (?\DateTime $dateDebut): Tag
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    public function getDateFin (): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin (?\DateTime $dateFin): Tag
    {
        $this->dateFin = $dateFin;

        return $this;
    }

}
