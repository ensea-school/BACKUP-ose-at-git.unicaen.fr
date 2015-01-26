<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleResultatService
 */
class FormuleResultatService
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
    private $heuresComplFi;

    /**
     * @var float
     */
    private $heuresComplFa;

    /**
     * @var float
     */
    private $heuresComplFc;

    /**
     * @var float
     */
    private $heuresComplFcMajorees;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleResultat
     */
    private $formuleResultat;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;


    /**
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultatService
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
     * @return FormuleResultatService
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
     * Set heuresComplFi
     *
     * @param float $heuresComplFi
     * @return FormuleResultatService
     */
    public function setHeuresComplFi($heuresComplFi)
    {
        $this->heuresComplFi = $heuresComplFi;

        return $this;
    }

    /**
     * Get heuresComplFi
     *
     * @return float 
     */
    public function getHeuresComplFi()
    {
        return $this->heuresComplFi;
    }

    /**
     * Set heuresComplFa
     *
     * @param float $heuresComplFa
     * @return FormuleResultatService
     */
    public function setHeuresComplFa($heuresComplFa)
    {
        $this->heuresComplFa = $heuresComplFa;

        return $this;
    }

    /**
     * Get heuresComplFa
     *
     * @return float 
     */
    public function getHeuresComplFa()
    {
        return $this->heuresComplFa;
    }

    /**
     * Set heuresComplFc
     *
     * @param float $heuresComplFc
     * @return FormuleResultatService
     */
    public function setHeuresComplFc($heuresComplFc)
    {
        $this->heuresComplFc = $heuresComplFc;

        return $this;
    }

    /**
     * Get heuresComplFc
     *
     * @return float 
     */
    public function getHeuresComplFc()
    {
        return $this->heuresComplFc;
    }

    /**
     * Set heuresComplFcMajorees
     *
     * @param float $heuresComplFcMajorees
     * @return FormuleResultatService
     */
    public function setHeuresComplFcMajorees($heuresComplFcMajorees)
    {
        $this->heuresComplFcMajorees = $heuresComplFcMajorees;

        return $this;
    }

    /**
     * Get heuresComplFcMajorees
     *
     * @return float 
     */
    public function getHeuresComplFcMajorees()
    {
        return $this->heuresComplFcMajorees;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleResultatService
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
     * @return FormuleResultatService
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
     * Set Service
     *
     * @param \Application\Entity\Db\Service $service
     * @return FormuleResultatService
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get Service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }
}
