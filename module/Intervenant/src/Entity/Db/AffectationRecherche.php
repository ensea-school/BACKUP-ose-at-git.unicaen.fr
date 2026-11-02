<?php

namespace Intervenant\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;


/**
 * AffectationRecherche
 */
class AffectationRecherche implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var integer
     */
    protected $structureId;

    /**
     * @var \Intervenant\Entity\Db\Intervenant
     */
    protected $intervenant;



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
     * Set structure
     *
     * @param $structureId int
     *
     * @return AffectationRecherche
     */
    public function setStructureId($structureId)
    {
        $this->structureId = $structureId;

        return $this;
    }



    /**
     * Get structure
     *
     * @return integer $structureId
     */
    public function getStructureId()
    {
        return $this->structureId;
    }



    /**
     * Set intervenant
     *
     * @param \Intervenant\Entity\Db\Intervenant $intervenant
     *
     * @return AffectationRecherche
     */
    public function setIntervenant(?\Intervenant\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    /**
     * Get intervenant
     *
     * @return \Intervenant\Entity\Db\Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }


    /**************************************************************************************************
     *                                      Début ajout
     **************************************************************************************************/

    /**
     * Retourne la représentation littérale de cet objet.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getStructure()->__toString();
    }

}
