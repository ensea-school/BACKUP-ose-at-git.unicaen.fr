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
    private $cloture = '0';

    /**
     * @var boolean
     */
    private $peutCloturerSaisie = '0';

    /**
     * @var boolean
     */
    private $toDelete = '0';

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
     * Set cloture
     *
     * @param boolean $cloture
     *
     * @return TblClotureRealise
     */
    public function setCloture($cloture)
    {
        $this->cloture = $cloture;

        return $this;
    }

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
     * Set peutCloturerSaisie
     *
     * @param boolean $peutCloturerSaisie
     *
     * @return TblClotureRealise
     */
    public function setPeutCloturerSaisie($peutCloturerSaisie)
    {
        $this->peutCloturerSaisie = $peutCloturerSaisie;

        return $this;
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
     * Set toDelete
     *
     * @param boolean $toDelete
     *
     * @return TblClotureRealise
     */
    public function setToDelete($toDelete)
    {
        $this->toDelete = $toDelete;

        return $this;
    }

    /**
     * Get toDelete
     *
     * @return boolean
     */
    public function getToDelete()
    {
        return $this->toDelete;
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     *
     * @return TblClotureRealise
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     *
     * @return TblClotureRealise
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
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

