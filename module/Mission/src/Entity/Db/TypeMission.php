<?php

namespace Mission\Entity\Db;

use Application\Interfaces\ParametreEntityInterface;
use Application\Traits\ParametreEntityTrait;
use Paiement\Entity\Db\TauxRemu;
use Plafond\Interfaces\PlafondDataInterface;
use Plafond\Interfaces\PlafondPerimetreInterface;

class TypeMission implements ParametreEntityInterface, PlafondPerimetreInterface, PlafondDataInterface
{
    use ParametreEntityTrait;

    protected ?int $id = null;

    protected ?string $code = null;

    protected ?string $libelle = null;

    protected ?TauxRemu $tauxRemu = null;

    protected ?TauxRemu $tauxRemuMajore = null;

    protected bool $accompagnementEtudiants = false;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): TypeMission
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): TypeMission
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?TauxRemu $tauxRemu): TypeMission
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    public function getTauxRemuMajore(): ?TauxRemu
    {
        return $this->tauxRemuMajore;
    }



    public function setTauxRemuMajore(?TauxRemu $tauxRemuMajore): self
    {
        $this->tauxRemuMajore = $tauxRemuMajore;

        return $this;
    }



    public function isAccompagnementEtudiants(): bool
    {
        return $this->accompagnementEtudiants;
    }



    public function setAccompagnementEtudiants(bool $accompagnementEtudiants): TypeMission
    {
        $this->accompagnementEtudiants = $accompagnementEtudiants;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
