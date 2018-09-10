<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\ModeleContratAwareTrait;

/**
 * ModeleContrat
 */
class ModeleContratBloc
{
    use ModeleContratAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $nom;

    /**
     * @var string
     */
    protected $requete;



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
     * @return ModeleContratBloc
     */
    public function setId(int $id): ModeleContratBloc
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }



    /**
     * @param string $nom
     *
     * @return ModeleContratBloc
     */
    public function setNom(string $nom): ModeleContratBloc
    {
        $this->nom = $nom;

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
     * @return ModeleContratBloc
     */
    public function setRequete(string $requete): ModeleContratBloc
    {
        $this->requete = $requete;

        return $this;
    }



    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNom();
    }
}
