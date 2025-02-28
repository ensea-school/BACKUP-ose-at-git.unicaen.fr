<?php

namespace Service\Entity\Db;

use Administration\Interfaces\ChampsAutresInterface;
use Administration\Traits\ChampsAutresTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MotifModificationServiceDu implements HistoriqueAwareInterface, ChampsAutresInterface
{
    use HistoriqueAwareTrait;
    use ChampsAutresTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $libelle = null;

    protected bool $decharge = false;

    protected float $multiplicateur = -1;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getCode(): ?string
    {
        return $this->code;
    }


    public function setCode(?string $code): MotifModificationServiceDu
    {
        $this->code = $code;

        return $this;
    }


    public function getLibelle(): ?string
    {
        return $this->libelle;
    }


    public function setLibelle(?string $libelle): MotifModificationServiceDu
    {
        $this->libelle = $libelle;

        return $this;
    }


    public function getDecharge(): bool
    {
        return $this->decharge;
    }


    public function setDecharge(bool $decharge): MotifModificationServiceDu
    {
        $this->decharge = $decharge;

        return $this;
    }


    public function getMultiplicateur(): float|int
    {
        return $this->multiplicateur;
    }


    public function setMultiplicateur(float|int $multiplicateur): MotifModificationServiceDu
    {
        $this->multiplicateur = $multiplicateur;

        return $this;
    }


    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
