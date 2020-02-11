<?php

namespace Application\Entity\Traits;

use Application\Entity\Db\AdresseNumeroCompl;
use Application\Entity\Db\Pays;
use Application\Entity\Db\Voirie;

trait AdresseTrait
{
    /**
     * @var string|null
     */
    private $addrPrecisions;

    /**
     * @var string|null
     */
    private $addrNumero;

    /**
     * @var AdresseNumeroCompl|null
     */
    private $addrNumeroCompl;

    /**
     * @var Voirie|null
     */
    private $addrVoirie;

    /**
     * @var string|null
     */
    private $addrVoie;

    /**
     * @var string|null
     */
    private $addrLieuDit;

    /**
     * @var string|null
     */
    private $addrCodePostal;

    /**
     * @var string|null
     */
    private $addrCommune;

    /**
     * @var Pays|null
     */
    private $addrPays;



    /**
     * @return string|null
     */
    public function getAdresse(bool $withIdentite = true): ?string
    {
        $adresse = [];

        if ($this->getAddrPrecisions()) $adresse[] = $this->getAddrPrecisions();

        $nv = [];
        if ($this->getAddrNumero()) $nv[] = $this->getAddrNumero();
        if ($this->getAddrNumeroCompl()) $nv[] = $this->getAddrNumeroCompl();
        if ($this->getAddrVoirie()) $nv[] = $this->getAddrVoirie();
        if ($this->getAddrVoie()) $nv[] = $this->getAddrVoie();
        if (!empty($nv)) {
            $adresse[] = implode(' ', $nv);
        }

        if ($this->getAddrLieuDit()) $adresse[] = $this->getAddrLieuDit();

        $cpcp = [];
        if ($this->getAddrCodePostal()) $cpcp[] = $this->getAddrCodePostal();
        if ($this->getAddrCommune()) $cpcp[] = $this->getAddrCommune();
        if ($this->getAddrPays()) $cpcp[] = $this->getAddrPays();
        if (!empty($cpcp)) {
            $adresse[] = implode(' ', $cpcp);
        }

        if (!empty($adresse)) {
            if ($withIdentite && $this instanceof \AdresseInterface && ($identite = $this->getAdresseIdentite())) {
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
    public function getAddrPrecisions(): ?string
    {
        return $this->addrPrecisions;
    }



    /**
     * @param string|null $addrPrecisions
     *
     * @return AdresseTrait
     */
    public function setAddrPrecisions(?string $addrPrecisions): self
    {
        $this->addrPrecisions = $addrPrecisions;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAddrNumero(): ?string
    {
        return $this->addrNumero;
    }



    /**
     * @param string|null $addrNumero
     *
     * @return AdresseTrait
     */
    public function setAddrNumero(?string $addrNumero): self
    {
        $this->addrNumero = $addrNumero;

        return $this;
    }



    /**
     * @return AdresseNumeroCompl|null
     */
    public function getAddrNumeroCompl(): ?AdresseNumeroCompl
    {
        return $this->addrNumeroCompl;
    }



    /**
     * @param AdresseNumeroCompl|null $addrNumeroCompl
     *
     * @return AdresseTrait
     */
    public function setAddrNumeroCompl(?AdresseNumeroCompl $addrNumeroCompl): self
    {
        $this->addrNumeroCompl = $addrNumeroCompl;

        return $this;
    }



    /**
     * @return Voirie|null
     */
    public function getAddrVoirie(): ?Voirie
    {
        return $this->addrVoirie;
    }



    /**
     * @param Voirie|null $addrVoirie
     *
     * @return AdresseTrait
     */
    public function setAddrVoirie(?Voirie $addrVoirie): self
    {
        $this->addrVoirie = $addrVoirie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAddrVoie(): ?string
    {
        return $this->addrVoie;
    }



    /**
     * @param string|null $addrVoie
     *
     * @return AdresseTrait
     */
    public function setAddrVoie(?string $addrVoie): self
    {
        $this->addrVoie = $addrVoie;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAddrLieuDit(): ?string
    {
        return $this->addrLieuDit;
    }



    /**
     * @param string|null $addrLieuDit
     *
     * @return AdresseTrait
     */
    public function setAddrLieuDit(?string $addrLieuDit): self
    {
        $this->addrLieuDit = $addrLieuDit;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAddrCodePostal(): ?string
    {
        return $this->addrCodePostal;
    }



    /**
     * @param string|null $addrCodePostal
     *
     * @return AdresseTrait
     */
    public function setAddrCodePostal(?string $addrCodePostal): self
    {
        $this->addrCodePostal = $addrCodePostal;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getAddrCommune(): ?string
    {
        return $this->addrCommune;
    }



    /**
     * @param string|null $addrCommune
     *
     * @return AdresseTrait
     */
    public function setAddrCommune(?string $addrCommune): self
    {
        $this->addrCommune = $addrCommune;

        return $this;
    }



    /**
     * @return Pays|null
     */
    public function getAddrPays(): ?Pays
    {
        return $this->addrPays;
    }



    /**
     * @param Pays|null $addrPays
     *
     * @return AdresseTrait
     */
    public function setAddrPays(?Pays $addrPays): self
    {
        $this->addrPays = $addrPays;

        return $this;
    }

}