<?php

namespace Indicateur\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Indicateur
 */
class Indicateur
{
    private int $id;

    private TypeIndicateur $typeIndicateur;

    private int $numero = 0;

    private bool $enabled = true;

    private int $ordre = 0;

    private string $libelleSingulier = 'Nouvel indicateur';

    private string $libellePluriel = 'Nouvel indicateur';

    private string $route = 'intervenant/voir';

    private bool $irrecevables = false;

    private Collection $notification;

    private bool $special = false;



    public function __construct()
    {
        $this->notification = new ArrayCollection();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return Indicateur
     */
    public function setId(int $id): Indicateur
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return TypeIndicateur
     */
    public function getTypeIndicateur(): TypeIndicateur
    {
        return $this->typeIndicateur;
    }



    /**
     * @param TypeIndicateur $typeIndicateur
     *
     * @return Indicateur
     */
    public function setTypeIndicateur(TypeIndicateur $typeIndicateur): Indicateur
    {
        $this->typeIndicateur = $typeIndicateur;

        return $this;
    }



    /**
     * @return int
     */
    public function getNumero(): int
    {
        return $this->numero;
    }



    /**
     * @param int $numero
     *
     * @return Indicateur
     */
    public function setNumero(int $numero): Indicateur
    {
        $this->numero = $numero;

        return $this;
    }



    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }



    /**
     * @param bool $enabled
     *
     * @return Indicateur
     */
    public function setEnabled(bool $enabled): Indicateur
    {
        $this->enabled = $enabled;

        return $this;
    }



    /**
     * @return int
     */
    public function getOrdre(): int
    {
        return $this->ordre;
    }



    /**
     * @param int $ordre
     *
     * @return Indicateur
     */
    public function setOrdre(int $ordre): Indicateur
    {
        $this->ordre = $ordre;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelleSingulier(): string
    {
        return $this->libelleSingulier;
    }



    /**
     * @param string $libelleSingulier
     *
     * @return Indicateur
     */
    public function setLibelleSingulier(string $libelleSingulier): Indicateur
    {
        $this->libelleSingulier = $libelleSingulier;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibellePluriel(): string
    {
        return $this->libellePluriel;
    }



    /**
     * @param string $libellePluriel
     *
     * @return Indicateur
     */
    public function setLibellePluriel(string $libellePluriel): Indicateur
    {
        $this->libellePluriel = $libellePluriel;

        return $this;
    }



    /**
     * @return string
     */
    public function getRoute(): string
    {
        return $this->route;
    }



    /**
     * @param string $route
     *
     * @return Indicateur
     */
    public function setRoute(string $route): Indicateur
    {
        $this->route = $route;

        return $this;
    }



    /**
     * @return bool
     */
    public function isIrrecevables(): bool
    {
        return $this->irrecevables;
    }



    /**
     * @param bool $irrecevables
     *
     * @return Indicateur
     */
    public function setIrrecevables(bool $irrecevables): Indicateur
    {
        $this->irrecevables = $irrecevables;

        return $this;
    }



    /**
     * @return Collection|NotificationIndicateur[]
     */
    public function getNotification()
    {
        return $this->notification;
    }



    /**
     * @return bool
     */
    public function isSpecial(): bool
    {
        return $this->special;
    }



    /**
     * @param bool $special
     * @return Indicateur
     */
    public function setSpecial(bool $special): Indicateur
    {
        $this->special = $special;
        return $this;
    }



    /**
     *
     * @return string
     */
    public function __toString()
    {
        return "Indicateur N°" . $this->getNumero();
    }



    public function getLibelle(int $count): string
    {
        if ($count > 1) {
            return sprintf($this->getLibellePluriel(), $count);
        } else {
            return sprintf($this->getLibelleSingulier(), $count);
        }
    }

}