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
     * @var int
     */
    protected ?int $id = null;

    /**
     * @var string|null
     */
    protected ?string $code = null;

    /**
     * @var string|null
     */
    protected ?string $libelle = null;



    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }



    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
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
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
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
     */
    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
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
