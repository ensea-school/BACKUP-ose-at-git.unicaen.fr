<?php

namespace Dossier\Entity\Db;


/**
 * Civilite
 */
class DossierAutreType
{

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var integer
     */
    protected $id;



    public function __toString()
    {
        return $this->getLibelle();
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
     * @return DossierAutreType $this
     */
    public function setLibelle(string $libelle): DossierAutreType
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
     * @return DossierAutreType $this
     */
    public function setCode(string $code): DossierAutreType
    {
        $this->code = $code;

        return $this;
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
     * @return DossierAutreType $this
     */
    public function setId(int $id): DossierAutreType
    {
        $this->id = $id;

        return $this;
    }

}
