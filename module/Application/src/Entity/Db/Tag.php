<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Tag implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $libelleCourt = null;

    protected ?string $libelleLong = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }


    public function setCode(?string $code): Tag
    {
        $this->code = $code;

        return $this;
    }


    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }


    public function setLibelleCourt(?string $libelleCourt): Tag
    {
        $this->libelleCourt = $libelleCourt;

        return $this;
    }


    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }


    public function setLibelleLong(?string $libelleLong): Tag
    {
        $this->libelleLong = $libelleLong;

        return $this;
    }


    public function __toString(): string
    {
        return $this->getLibelleLong() ?: $this->getLibelleCourt();
    }
}
