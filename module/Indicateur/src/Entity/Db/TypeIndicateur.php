<?php

namespace Indicateur\Entity\Db;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * TypeIndicateur
 */
class TypeIndicateur
{
    private int        $id;

    private int        $ordre;

    private string     $libelle;

    private Collection $indicateur;



    public function __construct()
    {
        $this->indicateur = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return int
     */
    public function getOrdre(): int
    {
        return $this->ordre;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * Get formuleResultatService
     *
     * @return Collection|Indicateur[]
     */
    public function getIndicateur(): Collection
    {
        return $this->formuleResultatService;
    }

}