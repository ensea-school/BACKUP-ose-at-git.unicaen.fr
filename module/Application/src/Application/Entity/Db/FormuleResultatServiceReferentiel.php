<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleResultatServiceReferentiel
 */
class FormuleResultatServiceReferentiel
{
    /**
     * @var float
     */
    private $serviceAssure;

    /**
     * @var float
     */
    private $heuresService;

    /**
     * @var float
     */
    private $heuresComplReferentiel;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\ServiceReferentiel
     */
    private $serviceReferentiel;


    /**
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultatServiceReferentiel
     */
    public function setServiceAssure($serviceAssure)
    {
        $this->serviceAssure = $serviceAssure;

        return $this;
    }

    /**
     * Get serviceAssure
     *
     * @return float 
     */
    public function getServiceAssure()
    {
        return $this->serviceAssure;
    }

    /**
     * Set heuresService
     *
     * @param float $heuresService
     * @return FormuleResultatServiceReferentiel
     */
    public function setHeuresService($heuresService)
    {
        $this->heuresService = $heuresService;

        return $this;
    }

    /**
     * Get heuresService
     *
     * @return float 
     */
    public function getHeuresService()
    {
        return $this->heuresService;
    }

    /**
     * Set heuresComplReferentiel
     *
     * @param float $heuresComplReferentiel
     * @return FormuleResultatServiceReferentiel
     */
    public function setHeuresComplReferentiel($heuresComplReferentiel)
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;

        return $this;
    }

    /**
     * Get heuresComplReferentiel
     *
     * @return float 
     */
    public function getHeuresComplReferentiel()
    {
        return $this->heuresComplReferentiel;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleResultatServiceReferentiel
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
     * Set formuleResultat
     *
     * @param \Application\Entity\Db\FormuleResultat $formuleResultat
     * @return FormuleResultatServiceReferentiel
     */
    public function setFormuleResultat(\Application\Entity\Db\FormuleResultat $formuleResultat = null)
    {
        $this->formuleResultat = $formuleResultat;

        return $this;
    }

    /**
     * Get formuleResultat
     *
     * @return \Application\Entity\Db\FormuleResultat 
     */
    public function getFormuleResultat()
    {
        return $this->formuleResultat;
    }

    /**
     * Set ServiceReferentiel
     *
     * @param \Application\Entity\Db\ServiceReferentiel $serviceReferentiel
     * @return FormuleResultatServiceReferentiel
     */
    public function setServiceReferentiel(\Application\Entity\Db\ServiceReferentiel $serviceReferentiel = null)
    {
        $this->serviceReferentiel = $serviceReferentiel;

        return $this;
    }

    /**
     * Get ServiceReferentiel
     *
     * @return \Application\Entity\Db\ServiceReferentiel 
     */
    public function getServiceReferentiel()
    {
        return $this->serviceReferentiel;
    }
}
