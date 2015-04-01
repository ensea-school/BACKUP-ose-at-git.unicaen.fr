<?php

namespace Application\Entity\Db;

/**
 * FormuleIntervenant
 */
class FormuleIntervenant
{
    /**
     * @var float
     */
    private $heuresServiceStatutaire;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleServiceModifie;

    /**
     *
     * @var boolean
     */
    protected $depassementServiceDuSansHC;


        /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleServiceReferentiel;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleServiceModifie = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleService = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleServiceReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get heuresServiceStatutaire
     *
     * @return float 
     */
    public function getHeuresServiceStatutaire()
    {
        return $this->heuresServiceStatutaire;
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
     * Get formuleServiceModifie
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleServiceModifie()
    {
        return $this->formuleServiceModifie;
    }

    /**
     *
     * @return FormuleServiceModifie
     */
    public function getUniqueFormuleServiceModifie()
    {
        $result = $this->getFormuleServiceModifie()->first();
        if (false === $result) $result = new FormuleServiceModifie;
        return $result;
    }

    /**
     * 
     * @return boolean
     */
    function getDepassementServiceDuSansHC()
    {
        return $this->depassementServiceDuSansHC;
    }

    /**
     *
     * @param boolean $depassementServiceDuSansHC
     * @return self
     */
    function setDepassementServiceDuSansHC($depassementServiceDuSansHC)
    {
        $this->depassementServiceDuSansHC = $depassementServiceDuSansHC;
        return $this;
    }

    /**
     * Get formuleService
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }

    /**
     * Get formuleServiceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleServiceReferentiel()
    {
        return $this->formuleServiceReferentiel;
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
}
