<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * IndicModifDossier
 */
class IndicModifDossier implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    const ATTR_NAME_DATE_NAISSANCE = 'DATE_NAISSANCE';

    /**
     * @var string
     */
    private $attrName;

    /**
     * @var string
     */
    private $attrOldSourceName;

    /**
     * @var string
     */
    private $attrOldValue;

    /**
     * @var string
     */
    private $attrNewSourceName;

    /**
     * @var string
     */
    private $attrNewValue;

    /**
     * @var boolean
     */
    private $estCreationDossier;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * Get attrName
     *
     * @return string
     */
    public function getAttrName()
    {
        return $this->attrName;
    }

    /**
     * Get attrOldSourceName
     *
     * @return string
     */
    public function getAttrOldSourceName()
    {
        return $this->attrOldSourceName;
    }

    /**
     * Get attrOldValue
     *
     * @return string
     */
    public function getAttrOldValue()
    {
        return $this->attrOldValue;
    }

    /**
     * Get attrNewSourceName
     *
     * @return string
     */
    public function getAttrNewSourceName()
    {
        return $this->attrNewSourceName;
    }

    /**
     * Get attrNewValue
     *
     * @return string
     */
    public function getAttrNewValue()
    {
        return $this->attrNewValue;
    }

    /**
     * Get estCreationDossier
     *
     * @return boolean
     */
    public function getEstCreationDossier()
    {
        return $this->estCreationDossier;
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
}

