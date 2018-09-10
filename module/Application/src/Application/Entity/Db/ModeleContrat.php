<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\StatutIntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;

/**
 * ModeleContrat
 */
class ModeleContrat
{
    use StatutIntervenantAwareTrait;
    use StructureAwareTrait;

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
    protected $contenu;

    /**
     * @var string
     */
    protected $requete;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $bloc;



    /**
     * PHP 5 allows developers to declare constructor methods for classes.
     * Classes which have a constructor method call this method on each newly-created object,
     * so it is suitable for any initialization that the object may need before it is used.
     *
     * Note: Parent constructors are not called implicitly if the child class defines a constructor.
     * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
     *
     * param [ mixed $args [, $... ]]
     *
     * @link https://php.net/manual/en/language.oop5.decon.php
     */
    public function __construct()
    {
        $this->bloc = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return ModeleContrat
     */
    public function setId(int $id): ModeleContrat
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
     * @return ModeleContrat
     */
    public function setLibelle(string $libelle): ModeleContrat
    {
        $this->libelle = $libelle;

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
     * @return ModeleContrat
     */
    public function setContenu(string $contenu): ModeleContrat
    {
        $this->contenu = $contenu;

        return $this;
    }



    /**
     * @return string
     */
    public function getRequete(): string
    {
        return $this->requete;
    }



    /**
     * @param string $requete
     *
     * @return ModeleContrat
     */
    public function setRequete(string $requete): ModeleContrat
    {
        $this->requete = $requete;

        return $this;
    }



    /**
     * Add bloc
     *
     * @param ModeleContratBloc $bloc
     *
     * @return ModeleContrat
     */
    public function addBloc(ModeleContratBloc $bloc): ModeleContrat
    {
        $this->bloc[] = $bloc;

        return $this;
    }



    /**
     * Remove bloc
     *
     * @param ModeleContratBloc $bloc
     *
     * @return ModeleContrat
     */
    public function removeBloc(ModeleContratBloc $bloc): ModeleContrat
    {
        $this->bloc->removeElement($bloc);

        return $this;
    }



    /**
     * Get bloc
     *
     * @return \Doctrine\Common\Collections\Collection|ModeleContratBloc[]
     */
    public function getBloc()
    {
        return $this->bloc;
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
