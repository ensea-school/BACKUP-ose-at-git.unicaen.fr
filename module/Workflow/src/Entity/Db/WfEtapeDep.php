<?php

namespace Workflow\Entity\Db;

use Application\Entity\Db\TypeIntervenant;

/**
 * WfEtapeDep
 *
 * @deprecated
 */
class WfEtapeDep
{
    /**
     * @var boolean
     */
    private $locale = false;

    /**
     * @var boolean
     */
    private $integrale = false;

    /**
     * @var boolean
     */
    private $partielle = false;

    /**
     * @var boolean
     */
    private $obligatoire = false;

    /**
     * @var boolean
     */
    private $active = true;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Workflow\Entity\Db\WfEtape
     */
    private $etapeSuiv;

    /**
     * @var \Workflow\Entity\Db\WfEtape
     */
    private $etapePrec;

    /**
     * @var \Intervenant\Entity\Db\TypeIntervenant
     */
    private $typeIntervenant;



    /**
     * Set locale
     *
     * @param boolean $locale
     *
     * @return WfEtapeDep
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }



    /**
     * Get locale
     *
     * @return boolean
     */
    public function getLocale()
    {
        return $this->locale;
    }



    /**
     * Set complete
     *
     * @param boolean $complete
     *
     * @return WfEtapeDep
     */
    public function setComplete($complete)
    {
        $this->complete = $complete;

        return $this;
    }



    /**
     * Get complete
     *
     * @return boolean
     */
    public function getComplete()
    {
        return $this->complete;
    }



    /**
     * Set partielle
     *
     * @param boolean $partielle
     *
     * @return WfEtapeDep
     */
    public function setPartielle($partielle)
    {
        $this->partielle = $partielle;

        return $this;
    }



    /**
     * Get partielle
     *
     * @return boolean
     */
    public function getPartielle()
    {
        return $this->partielle;
    }



    /**
     * @return boolean
     */
    public function getObligatoire()
    {
        return $this->obligatoire;
    }



    /**
     * @param boolean $obligatoire
     *
     * @return WfEtapeDep
     */
    public function setObligatoire($obligatoire)
    {
        $this->obligatoire = $obligatoire;

        return $this;
    }



    /**
     * Set integrale
     *
     * @param boolean $integrale
     *
     * @return WfEtapeDep
     */
    public function setIntegrale($integrale)
    {
        $this->integrale = $integrale;

        return $this;
    }



    /**
     * Get integrale
     *
     * @return boolean
     */
    public function getIntegrale()
    {
        return $this->integrale;
    }



    /**
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }



    /**
     * @param boolean $active
     *
     * @return WfEtapeDep
     */
    public function setActive($active)
    {
        $this->active = $active;

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
     * Set etapeSuiv
     *
     * @param \Workflow\Entity\Db\WfEtape $etapeSuiv
     *
     * @return WfEtapeDep
     */
    public function setEtapeSuiv(\Workflow\Entity\Db\WfEtape $etapeSuiv = null)
    {
        $this->etapeSuiv = $etapeSuiv;

        return $this;
    }



    /**
     * Get etapeSuiv
     *
     * @return \Workflow\Entity\Db\WfEtape
     */
    public function getEtapeSuiv()
    {
        return $this->etapeSuiv;
    }



    /**
     * Set etapePrec
     *
     * @param \Workflow\Entity\Db\WfEtape $etapePrec
     *
     * @return WfEtapeDep
     */
    public function setEtapePrec(\Workflow\Entity\Db\WfEtape $etapePrec = null)
    {
        $this->etapePrec = $etapePrec;

        return $this;
    }



    /**
     * Get etapePrec
     *
     * @return \Workflow\Entity\Db\WfEtape
     */
    public function getEtapePrec()
    {
        return $this->etapePrec;
    }



    /**
     * @return TypeIntervenant
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }



    /**
     * @param TypeIntervenant $typeIntervenant
     *
     * @return WfEtapeDep
     */
    public function setTypeIntervenant($typeIntervenant)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }

}

