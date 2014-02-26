<?php

namespace Import\Model\Entity\Structure;

use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Etablissement extends Entity {

    /**
     * Identifiant
     *
     * @var integer
     */
    protected $id;

    /**
     * Libellé
     *
     * @var string
     */
    protected $libelle;

    /**
     * Localisation
     *
     * @var string
     */
    protected $localisation;

    /**
     * Département
     *
     * @var string
     */
    protected $departement;


    


    public function getId()
    {
        return $this->id;
    }

    public function getLibelle()
    {
        return $this->libelle;
    }

    public function getLocalisation()
    {
        return $this->localisation;
    }

    public function getDepartement()
    {
        return $this->departement;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;
        return $this;
    }

    public function setDepartement($departement)
    {
        $this->departement = $departement;
        return $this;
    }

}