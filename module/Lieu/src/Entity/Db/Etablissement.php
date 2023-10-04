<?php

namespace Lieu\Entity\Db;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

/**
 * Etablissement
 */
class Etablissement implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var string
     */
    protected $departement;

    /**
     * @var string
     */
    protected $libelle;

    /**
     * @var string
     */
    protected $localisation;

    /**
     * @var integer
     */
    protected $id;




    /**
     * Set departement
     *
     * @param string $departement
     *
     * @return Etablissement
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }



    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
    }



    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return Etablissement
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }



    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }



    /**
     * Set localisation
     *
     * @param string $localisation
     *
     * @return Etablissement
     */
    public function setLocalisation($localisation)
    {
        $this->localisation = $localisation;

        return $this;
    }



    /**
     * Get localisation
     *
     * @return string
     */
    public function getLocalisation()
    {
        return $this->localisation;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }



    /**************************************************************************************************
     *                                      Début ajout
     **************************************************************************************************/

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getLibelle();
    }

}
