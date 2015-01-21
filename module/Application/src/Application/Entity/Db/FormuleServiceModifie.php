<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleServiceModifie
 */
class FormuleServiceModifie
{
    /**
     * @var float
     */
    private $heures;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleIntervenant
     */
    private $formuleIntervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set heures
     *
     * @param float $heures
     * @return FormuleServiceModifie
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleServiceModifie
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Set formuleIntervenant
     *
     * @param \Application\Entity\Db\FormuleIntervenant $formuleIntervenant
     * @return FormuleServiceModifie
     */
    public function setFormuleIntervenant(\Application\Entity\Db\FormuleIntervenant $formuleIntervenant = null)
    {
        $this->formuleIntervenant = $formuleIntervenant;

        return $this;
    }

    /**
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant 
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return FormuleServiceModifie
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
