<?php

namespace Intervenant\Entity\Db;


use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * Description of Statut
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class TypeNote implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $libelle;

    /**
     * @var string
     */
    private $code;



    public function __construct()
    {

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
     * @return TypeNote
     */
    public function setId(int $id): TypeNote
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return TypeNote
     */
    public function setLibelle(string $libelle): TypeNote
    {
        $this->libelle = $libelle;

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
     * @return TypeNote
     */
    public function setCode(string $code): TypeNote
    {
        $this->code = $code;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
