<?php

namespace Paiement\Entity\Db;


class JourFerie
{

    private ?int $id = null;

    private ?\DateTime $dateJour = null;

    private ?String $libelle = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): JourFerie
    {
        $this->id = $id;

        return $this;
    }



    public function getDateJour(): ?\DateTime
    {
        return $this->dateJour;
    }



    public function setDateJour(?\DateTime $dateJour): JourFerie
    {
        $this->dateJour = $dateJour;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): JourFerie
    {
        $this->libelle = $libelle;

        return $this;
    }



}
