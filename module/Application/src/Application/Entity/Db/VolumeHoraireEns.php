<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * VolumeHoraireEns
 */
class VolumeHoraireEns implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /**
     * @var float
     */
    protected $heures;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\TypeIntervention
     */
    protected $typeIntervention;

    /**
     * @var \Application\Entity\Db\ElementDiscipline
     */
    protected $elementDiscipline;



    /**
     * Set heures
     *
     * @param float $heures
     *
     * @return VolumeHoraireEns
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
     * Set sourceCode
     *
     * @param string $sourceCode
     *
     * @return VolumeHoraireEns
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }



    /**
     * Get sourceCode
     *
     * @return string
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     *
     * @return VolumeHoraireEns
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }



    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     *
     *
     * /**
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     *
     * @return VolumeHoraireEns
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }



    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }



    /**
     * Set elementDiscipline
     *
     * @param \Application\Entity\Db\ElementDiscipline $elementDiscipline
     *
     * @return VolumeHoraireEns
     */
    public function setElementDiscipline(\Application\Entity\Db\ElementDiscipline $elementDiscipline = null)
    {
        $this->elementDiscipline = $elementDiscipline;

        return $this;
    }



    /**
     * Get elementDiscipline
     *
     * @return \Application\Entity\Db\ElementDiscipline
     */
    public function getElementDiscipline()
    {
        return $this->elementDiscipline;
    }
}
