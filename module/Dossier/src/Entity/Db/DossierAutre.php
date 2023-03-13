<?php

namespace Dossier\Entity\Db;


class DossierAutre
{

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $contenu;

    /**
     * @var integer
     */
    protected $obligatoire;

    /**
     * @var DossierAutreType
     */
    protected $type;

    /**
     * @var string
     */
    protected $jsonValue;

    /**
     * @var string
     */
    protected $sqlValue;



    public function __toString()
    {
        return $this->getLibelle();
    }



    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }



    /**
     * @return string
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return DossierAutre $this
     */
    public function setLibelle(?string $libelle): DossierAutre
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }



    /**
     * @param string $description
     *
     * @return DossierAutre $this
     */
    public function setDescription(?string $description): DossierAutre
    {
        $this->description = $description;

        return $this;
    }



    /**
     * @return string
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }



    /**
     * @param string $contenu
     *
     * @return DossierAutre $this
     */
    public function setContenu(?string $contenu): DossierAutre
    {
        $this->contenu = $contenu;

        return $this;
    }



    /**
     * @return int
     */
    public function isObligatoire(): bool
    {
        return $this->obligatoire;
    }



    /**
     * @param int $obligatoire
     *
     * @return DossierAutre $this
     */
    public function setObligatoire(int $obligatoire): DossierAutre
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    /**
     * @return DossierAutreType
     */
    public function getType(): DossierAutreType
    {
        return $this->type;
    }



    /**
     * @param DossierAutreType $type
     *
     * @return DossierAutre $this
     */
    public function setType(DossierAutreType $type): DossierAutre
    {
        $this->type = $type;

        return $this;
    }



    /**
     * @return string
     */
    public function getJsonValue(): ?string
    {
        return $this->jsonValue;
    }



    /**
     * @param $jsonValue string
     *
     * @return DossierAutre $this
     */
    public function setJsonValue(string $jsonValue): DossierAutre
    {
        $this->jsonValue = $jsonValue;

        return $this;
    }



    /**
     * @return string
     */
    public function getSqlValue(): ?string
    {
        return $this->sqlValue;
    }



    /**
     * @param $sqlValue string
     *
     * @return DossierAutre $this
     */
    public function setSqlValue(string $sqlValue): DossierAutre
    {
        $this->sqlValue = $sqlValue;

        return $this;
    }

}
