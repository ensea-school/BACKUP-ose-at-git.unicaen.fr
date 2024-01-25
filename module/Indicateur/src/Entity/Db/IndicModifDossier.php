<?php

namespace Indicateur\Entity\Db;

use Intervenant\Entity\Db\IntervenantAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * IndicModifDossier
 */
class IndicModifDossier implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;

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
     * Get attrName
     *
     * @return string
     */
    public function getAttrName()
    {
        return $this->attrName;
    }



    /**
     * Set attrName
     *
     * @return IndicModifDossier
     */
    public function setAttrName(string $attrName): IndicModifDossier
    {
        $this->attrName = $attrName;

        return $this;
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
     * Set attrOldSourceName
     *
     * @param string $source
     *
     * @return IndicModifDossier
     */
    public function setAttrOldSourceName(string $source): IndicModifDossier
    {
        $this->attrOldSourceName = $source;

        return $this;
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
     * Set attrOldValue
     *
     * @param string|null $oldValue
     *
     * @return IndicModifDossier
     */
    public function setAttrOldValue(?string $oldValue): IndicModifDossier
    {
        $this->attrOldValue = $oldValue;

        return $this;
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
     * Set attrNewSourceName
     *
     * @param string $source
     *
     * @return IndicModifDossier
     */
    public function setAttrNewSourceName(string $source): IndicModifDossier
    {
        $this->attrNewSourceName = $source;

        return $this;
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
     * Set attrNewValue
     *
     * @param string $newValue
     *
     * @return IndicModifDossier
     */
    public function setAttrNewValue(string $newValue): IndicModifDossier
    {
        $this->attrNewValue = $newValue;

        return $this;
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
     * Set estCreationDossier
     *
     * @param boolean $estCreationDossier
     *
     * @return IndicModifDossier
     */
    public function setEstCreationDossier(bool $estCreationDossier): IndicModifDossier
    {
        $this->estCreationDossier = $estCreationDossier;

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

}

