<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Departement
 */
class Departement implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $code;

    /**
     * @var string|null
     */
    protected $libelle;



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
     * @return Departement
     */
    public function setId(?int $id): Departement
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }



    /**
     * @param string|null $code
     *
     * @return Departement
     */
    public function setCode(?string $code): Departement
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
     * @return Departement
     */
    public function setLibelle(?string $libelle): Departement
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCode() . " - " . $this->getLibelle();
    }



    /**
     * @return bool
     */
    public function inIleDeFrance(): bool
    {
        $intCode = (int)$this->getCode();

        return in_array($intCode, [78, 91, 92, 93, 94, 95]);
    }

}
