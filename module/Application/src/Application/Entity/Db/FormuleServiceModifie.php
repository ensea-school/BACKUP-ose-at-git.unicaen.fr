<?php

namespace Application\Entity\Db;

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
     * @var float
     */
    private $heuresDecharge;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\FormuleIntervenant
     */
    private $formuleIntervenant;

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
     * Get heuresDecharge
     *
     * @return float
     */
    public function getHeuresDecharge()
    {
        return $this->heuresDecharge;
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
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant 
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }
}
