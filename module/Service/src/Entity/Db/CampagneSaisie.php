<?php

namespace Service\Entity\Db;


use Application\Constants;
use Application\Entity\Db\Traits\AnneeAwareTrait;
use Intervenant\Entity\Db\TypeIntervenantAwareTrait;
use Utilisateur\Acl\Role;

class CampagneSaisie
{
    use AnneeAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use TypeIntervenantAwareTrait;

    protected ?int       $id                 = null;

    protected ?\DateTime $dateDebut          = null;

    protected ?\DateTime $dateFin            = null;

    protected ?string    $messageIntervenant = null;

    protected ?string    $messageAutres      = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): CampagneSaisie
    {
        $this->id = $id;

        return $this;
    }



    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut(?\DateTime $dateDebut): CampagneSaisie
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin(?\DateTime $dateFin): CampagneSaisie
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    public function getMessageIntervenant(): ?string
    {
        return $this->messageIntervenant;
    }



    public function setMessageIntervenant(?string $messageIntervenant): CampagneSaisie
    {
        $this->messageIntervenant = $messageIntervenant;

        return $this;
    }



    public function getMessageAutres(): ?string
    {
        return $this->messageAutres;
    }



    public function setMessageAutres(?string $messageAutres): CampagneSaisie
    {
        $this->messageAutres = $messageAutres;

        return $this;
    }



    public function getMessage(bool $forIntervenant = true): string
    {
        if ($forIntervenant) {
            $message = $this->getMessageIntervenant();
        } else {
            $message = $this->getMessageAutres();
        }

        $dateDebut = $this->dateDebut ? $this->dateDebut->format(Constants::DATE_FORMAT) : '[Pas de date définie]';
        $dateFin   = $this->dateFin ? $this->dateFin->format(Constants::DATE_FORMAT) : '[Pas de date définie]';

        return str_replace([':dateDebut', ':dateFin'], [$dateDebut, $dateFin], $message ?? '');
    }



    /**
     * Retourne true si la date transmise ou à défaut la date du jour est dans la période, false sinon
     *
     * @return bool
     */
    public function estOuverte(?\DateTime $date = null): bool
    {
        $f = 'Y-m-d';

        if (empty($date)) $date = new \DateTime();

        $dc = $date->format($f);
        $dd = $this->dateDebut ? $this->dateDebut->format($f) : $dc;
        $df = $this->dateFin ? $this->dateFin->format($f) : $dc;

        if ($dc < $dd) return false;
        if ($dc > $df) return false;

        return true;
    }

}