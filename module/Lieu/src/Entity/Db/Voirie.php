<?php

namespace Lieu\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Voirie
 */
class Voirie implements HistoriqueAwareInterface, ImportAwareInterface
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
    protected $codeRh;

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
     * @return Voirie
     */
    public function setId(?int $id): Voirie
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
     * @return Voirie
     */
    public function setCode(?string $code): Voirie
    {
        $this->code = $code;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getCodeRh(): ?string
    {
        return $this->codeRh;
    }


    /**
     * @param string|null $codeRh
     *
     * @return Voirie
     */
    public function setCodeRh(?string $codeRh): Voirie
    {
        $this->codeRh = $codeRh;

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
     * @return Voirie
     */
    public function setLibelle(?string $libelle): Voirie
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
        return $this->getLibelle();
    }

}
