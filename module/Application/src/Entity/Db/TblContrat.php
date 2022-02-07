<?php

namespace Application\Entity\Db;

/**
 * TblContrat
 */
class TblContrat
{
    /**
     * @var float
     */
    private $edite = false;

    /**
     * @var float
     */
    private $nbvh = false;

    /**
     * @var boolean
     */
    private $contrat = false;

    /**
     * @var float
     */
    private $signe = 0;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Get edite
     *
     * @return float
     */
    public function getEdite()
    {
        return $this->edite;
    }



    /**
     * Get nbvh
     *
     * @return float
     */
    public function getNbvh()
    {
        return $this->nbvh;
    }



    /**
     * Get peutAvoirContrat
     *
     * @return boolean
     */
    public function hasContrat()
    {
        return $this->contrat;
    }



    /**
     * Get signe
     *
     * @return float
     */
    public function getSigne()
    {
        return $this->signe;
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



    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }



    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }



    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}

