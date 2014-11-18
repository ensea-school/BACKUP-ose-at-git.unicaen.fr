<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleServiceDu
 */
class FormuleServiceDu
{
    /**
     * @var float
     */
    private $serviceDu;

    /**
     * @var float
     */
    private $serviceDuModification;

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
     * Set serviceDu
     *
     * @param float $serviceDu
     * @return FormuleServiceDu
     */
    public function setServiceDu($serviceDu)
    {
        $this->serviceDu = $serviceDu;

        return $this;
    }

    /**
     * Get serviceDu
     *
     * @return float 
     */
    public function getServiceDu()
    {
        return $this->serviceDu;
    }

    /**
     * Set serviceDuModification
     *
     * @param float $serviceDuModification
     * @return FormuleServiceDu
     */
    public function setServiceDuModification($serviceDuModification)
    {
        $this->serviceDuModification = $serviceDuModification;

        return $this;
    }

    /**
     * Get serviceDuModification
     *
     * @return float 
     */
    public function getServiceDuModification()
    {
        return $this->serviceDuModification;
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
     * @return FormuleServiceDu
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
     * @return FormuleServiceDu
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
