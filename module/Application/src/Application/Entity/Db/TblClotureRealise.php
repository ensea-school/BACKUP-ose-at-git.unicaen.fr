<?php

namespace Application\Entity\Db;

/**
 * TblClotureRealise
 */
class TblClotureRealise
{
    /**
     * @var boolean
     */
    private $cloture = false;

    /**
     * @var boolean
     */
    private $peutCloturerSaisie = false;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;



    /**
     * Get cloture
     *
     * @return boolean
     */
    public function getCloture()
    {
        return $this->cloture;
    }



    /**
     * Get peutCloturerSaisie
     *
     * @return boolean
     */
    public function getPeutCloturerSaisie()
    {
        return $this->peutCloturerSaisie;
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

