<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;


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
     * @var integer
     */

    protected $disable;

    /**
     * @var DossierAutreType
     */
    protected $type;



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
    public function getLibelle(): string
    {
        return $this->libelle;
    }



    /**
     * @param string $libelle
     *
     * @return DossierAutre $this
     */
    public function setLibelle(string $libelle): DossierAutre
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }



    /**
     * @param string $description
     *
     * @return DossierAutre $this
     */
    public function setDescription(string $description): DossierAutre
    {
        $this->description = $description;

        return $this;
    }



    /**
     * @return string
     */
    public function getContenu(): string
    {
        return $this->contenu;
    }



    /**
     * @param string $contenu
     *
     * @return DossierAutre $this
     */
    public function setContenu(string $contenu): DossierAutre
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
     * @return boolean
     */
    public function isDisable(): bool
    {
        return $this->disable;
    }



    /**
     * @param int $disable
     *
     * @return DossierAutre $this
     */
    public function setDisable(int $disable): DossierAutre
    {
        $this->disable = $disable;

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

}
