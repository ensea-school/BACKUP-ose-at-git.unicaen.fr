<?php

namespace Lieu\Entity;

use Application\Interfaces\AdresseInterface;
use Lieu\Entity\Db\AdresseNumeroCompl;
use Lieu\Entity\Db\Pays;
use Lieu\Entity\Db\Voirie;

trait AdresseTrait
{
    /**
     * @var string|null
     */
    private $adressePrecisions;

    /**
     * @var string|null
     */
    private $adresseNumero;

    /**
     * @var AdresseNumeroCompl|null
     */
    private $adresseNumeroCompl;

    /**
     * @var Voirie|null
     */
    private $adresseVoirie;

    /**
     * @var string|null
     */
    private $adresseVoie;

    /**
     * @var string|null
     */
    private $adresseLieuDit;

    /**
     * @var string|null
     */
    private $adresseCodePostal;

    /**
     * @var string|null
     */
    private $adresseCommune;

    /**
     * @var Pays|null
     */
    private $adressePays;



    /**
     * @return string|null
     */
    public function getAdresse(bool $withIdentite = true): ?string
    {
        $adresse = [];

        if ($this->getAdressePrecisions()) $adresse[] = $this->getAdressePrecisions();

        $nv = [];
        if (!empty(trim($this->getAdresseNumero() ?? ''))) $nv[] = $this->getAdresseNumero();
        if (!empty(trim($this->getAdresseNumeroCompl() ?? ''))) $nv[] = $this->getAdresseNumeroCompl();
        if (!empty(trim($this->getAdresseVoirie() ?? ''))) $nv[] = $this->getAdresseVoirie();
        if (!empty(trim($this->getAdresseVoie() ?? ''))) $nv[] = $this->getAdresseVoie();

        if (!empty($nv)) {
            $adresse[] = implode(' ', $nv);
        }

        if ($this->getAdresseLieuDit()) $adresse[] = $this->getAdresseLieuDit();

        $cpcp = [];
        if (!empty(trim($this->getAdresseCodePostal()))) $cpcp[] = $this->getAdresseCodePostal();
        if (!empty(trim($this->getAdresseCommune()))) $cpcp[] = $this->getAdresseCommune();
        if (!empty(trim($this->getAdressePays()))) $cpcp[] = $this->getAdressePays();
        if (!empty($cpcp)) {
            $adresse[] = implode(' ', $cpcp);
        }

        if (!empty($adresse)) {
            if ($withIdentite && $this instanceof AdresseInterface && ($identite = $this->getAdresseIdentite())) {
                $adresse = [$identite] + $adresse;
            }

            return implode("\n", $adresse);
        } else {
            return null;
        }
    }



    /**
     * @return string|null
     */
    public function getAdressePrecisions(): ?string
    {
        return $this->adressePrecisions;
    }



    /**
     * @param string|null $adressePrecisions
     *
     * @return AdresseTrait
     */
    public function setAdressePrecisions(?string $adressePrecisions): self
    {
        $this->adressePrecisions = $adressePrecisions;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseNumero(): ?string
    {
        return $this->adresseNumero;
    }



    /**
     * @param string|null $adresseNumero
     *
     * @return AdresseTrait
     */
    public function setAdresseNumero(?string $adresseNumero): self
    {
        $this->adresseNumero = $adresseNumero;

        return $this;
    }



    /**
     * @return AdresseNumeroCompl|null
     */
    public function getAdresseNumeroCompl(): ?AdresseNumeroCompl
    {
        return $this->adresseNumeroCompl;
    }



    /**
     * @param AdresseNumeroCompl|null $adresseNumeroCompl
     *
     * @return AdresseTrait
     */
    public function setAdresseNumeroCompl(?AdresseNumeroCompl $adresseNumeroCompl): self
    {
        $this->adresseNumeroCompl = $adresseNumeroCompl;

        return $this;
    }



    /**
     * @return Voirie|null
     */
    public function getAdresseVoirie(): ?Voirie
    {
        return $this->adresseVoirie;
    }



    /**
     * @param Voirie|null $adresseVoirie
     *
     * @return AdresseTrait
     */
    public function setAdresseVoirie(?Voirie $adresseVoirie): self
    {
        $this->adresseVoirie = $adresseVoirie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseVoie(): ?string
    {
        return $this->adresseVoie;
    }



    /**
     * @param string|null $adresseVoie
     *
     * @return AdresseTrait
     */
    public function setAdresseVoie(?string $adresseVoie): self
    {
        $this->adresseVoie = $adresseVoie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseLieuDit(): ?string
    {
        return $this->adresseLieuDit;
    }



    /**
     * @param string|null $adresseLieuDit
     *
     * @return AdresseTrait
     */
    public function setAdresseLieuDit(?string $adresseLieuDit): self
    {
        $this->adresseLieuDit = $adresseLieuDit;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseCodePostal(): ?string
    {
        return $this->adresseCodePostal;
    }



    /**
     * @param string|null $adresseCodePostal
     *
     * @return AdresseTrait
     */
    public function setAdresseCodePostal(?string $adresseCodePostal): self
    {
        $this->adresseCodePostal = $adresseCodePostal;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAdresseCommune(): ?string
    {
        return $this->adresseCommune;
    }



    /**
     * @param string|null $adresseCommune
     *
     * @return AdresseTrait
     */
    public function setAdresseCommune(?string $adresseCommune): self
    {
        $this->adresseCommune = $adresseCommune;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getAdressePays(): ?Pays
    {
        return $this->adressePays;
    }



    /**
     * @param Pays|null $adressePays
     *
     * @return AdresseTrait
     */
    public function setAdressePays(?Pays $adressePays): self
    {
        $this->adressePays = $adressePays;

        return $this;
    }

}