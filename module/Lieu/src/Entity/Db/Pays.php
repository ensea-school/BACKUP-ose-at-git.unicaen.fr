<?php

namespace Lieu\Entity\Db;

use DateTime;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Pays
 */
class Pays implements HistoriqueAwareInterface, ImportAwareInterface
{
    const FRANCE = 'france';

    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected ?int $id = null;

    /**
     * @var string
     */
    protected ?string $code = null;

    /**
     * @var string
     */
    protected ?string $codeIso3 = null;


    /**
     * @var string|null
     */
    protected ?string $libelle = null;

    /**
     * @var bool|null
     */
    protected ?bool $temoinUe = false;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $validiteDebut = null;

    /**
     * @var DateTime|null
     */
    protected ?DateTime $validiteFin = null;


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @param int|null $id
     *
     * @return Pays
     */
    public function setId(?int $id): Pays
    {
        $this->id = $id;

        return $this;
    }


    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }


    /**
     * @param string $code
     *
     * @return Pays
     */
    public function setCode(?string $code): Pays
    {
        $this->code = $code;

        return $this;
    }


    /**
     * @return string
     */
    public function getCodeIso3(): ?string
    {
        return $this->codeIso3;
    }


    /**
     * @param string $codeIso3
     *
     * @return Pays
     */
    public function setCodeIso3(?string $codeIso3): Pays
    {
        $this->codeIso3 = $codeIso3;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }


    /**
     * @param string|null $libelle
     *
     * @return Pays
     */
    public function setLibelle(?string $libelle): Pays
    {
        $this->libelle = $libelle;

        return $this;
    }


    /**
     * @return bool|null
     */
    public function getTemoinUe(): ?bool
    {
        return $this->temoinUe;
    }


    /**
     * @param bool|null $temoinUe
     *
     * @return Pays
     */
    public function setTemoinUe(?bool $temoinUe): Pays
    {
        $this->temoinUe = $temoinUe;

        return $this;
    }


    /**
     * @return DateTime|null
     */
    public function getValiditeDebut(): ?DateTime
    {
        return $this->validiteDebut;
    }


    /**
     * @param DateTime|null $validiteDebut
     *
     * @return Pays
     */
    public function setValiditeDebut(?DateTime $validiteDebut): Pays
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }


    /**
     * @return DateTime|null
     */
    public function getValiditeFin(): ?DateTime
    {
        return $this->validiteFin;
    }


    /**
     * @param DateTime|null $validiteFin
     *
     * @return Pays
     */
    public function setValiditeFin(?DateTime $validiteFin): Pays
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }


    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }


    public function isFrance(): bool
    {
        return \UnicaenApp\Util::reduce($this->getLibelle()) == self::FRANCE;
    }
}
