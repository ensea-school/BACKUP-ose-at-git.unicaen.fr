<?php

namespace Application\Entity\Db;

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
     * @var int|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $libelle;

    /**
     * @var bool|null
     */
    protected $temoinUe;

    /**
     * @var \DateTime|null
     */
    protected $validiteDebut;

    /**
     * @var \DateTime|null
     */
    protected $validiteFin;



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
    public function getCode(): string
    {
        return $this->code;
    }



    /**
     * @param string $code
     *
     * @return Pays
     */
    public function setCode(string $code): Pays
    {
        $this->code = $code;

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
     * @return \DateTime|null
     */
    public function getValiditeDebut(): ?\DateTime
    {
        return $this->validiteDebut;
    }



    /**
     * @param \DateTime|null $validiteDebut
     *
     * @return Pays
     */
    public function setValiditeDebut(?\DateTime $validiteDebut): Pays
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }



    /**
     * @return \DateTime|null
     */
    public function getValiditeFin(): ?\DateTime
    {
        return $this->validiteFin;
    }



    /**
     * @param \DateTime|null $validiteFin
     *
     * @return Pays
     */
    public function setValiditeFin(?\DateTime $validiteFin): Pays
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
