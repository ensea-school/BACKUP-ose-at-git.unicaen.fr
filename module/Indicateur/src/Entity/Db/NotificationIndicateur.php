<?php

namespace Indicateur\Entity\Db;

use Application\Constants;
use DateTime;
use Utilisateur\Entity\Db\Affectation;

/**
 * NotificationIndicateur
 */
class NotificationIndicateur
{
    const PERIODE_HEURE_3 = 10800;  // 60*60*3    = 3h
    const PERIODE_HEURE_6 = 21600;  // 60*60*6    = 6h
    const PERIODE_JOUR    = 86400;  // 60*60*24   = 1j
    const PERIODE_SEMAINE = 604800; // 60*60*24*7 = 7j

    /**
     * Liste des féquences possibles.
     *
     * Attention, les fréquences "4 par jour" (période = 3h) et "2 par jour" (période = 6h)
     * n'ont de sens qu'en accord avec la configuration du CRON chargé d'exécuter le script de notification.
     * Par exemple, un CRON configuré pour se réveiller chaque jour de 7h à 18h toutes les heures pourra
     * honorer ces fréquences :
     * - "4 par jour" (période = 3h) : notification possible à 7h, 10h, 13h puis 16h.
     * - "2 par jour" (période = 6h) : notification possible à 7h puis 13h.
     *
     * @var array
     */
    static public         $frequences     = [
        self::PERIODE_HEURE_3 => "4 par jour",
        self::PERIODE_HEURE_6 => "2 par jour",
        self::PERIODE_JOUR    => "1 par jour",
        self::PERIODE_SEMAINE => "1 par semaine",
    ];

    protected int         $id;

    protected Affectation $affectation;

    protected Indicateur  $indicateur;

    protected ?string     $frequence      = null;

    protected bool        $inHome         = false;

    protected ?DateTime   $dateAbonnement = null;

    protected ?DateTime   $dateDernNotif  = null;



    public function getId(): int
    {
        return $this->id;
    }



    public function setIndicateur(Indicateur $indicateur): self
    {
        $this->indicateur = $indicateur;

        return $this;
    }



    public function getIndicateur(): Indicateur
    {
        return $this->indicateur;
    }



    public function getAffectation(): Affectation
    {
        return $this->affectation;
    }



    public function setAffectation(Affectation $affectation): self
    {
        $this->affectation = $affectation;

        return $this;
    }



    public function setFrequence(string $frequence): self
    {
        $this->frequence = $frequence;

        return $this;
    }



    public function getFrequence(): ?string
    {
        return $this->frequence;
    }



    public function getInHome(): bool
    {
        return $this->inHome;
    }



    public function setInHome(bool $inHome): self
    {
        $this->inHome = $inHome;

        return $this;
    }



    public function getFrequenceToString(): string
    {
        return static::$frequences[$this->getFrequence()];
    }



    public function setDateDernNotif(DateTime $date): self
    {
        $this->dateDernNotif = $date;

        return $this;
    }



    public function getDateDernNotif(): ?DateTime
    {
        return $this->dateDernNotif;
    }



    public function setDateAbonnement(DateTime $date): self
    {
        $this->dateAbonnement = $date;

        return $this;
    }



    public function getDateAbonnement(): ?DateTime
    {
        return $this->dateAbonnement;
    }



    public function getExtraInfos(): string
    {
        $infos = "Abonnement : " . $this->getDateAbonnement()->format(Constants::DATETIME_FORMAT);

        $infos .= "<br />Structure : " . ($this->getAffectation()->getStructure() ?: "aucune");

        if (($dernNotif = $this->getDateDernNotif())) {
            $infos .= "<br />Dernière notification : " . $dernNotif->format(Constants::DATETIME_FORMAT);
        }

        return $infos;
    }
}