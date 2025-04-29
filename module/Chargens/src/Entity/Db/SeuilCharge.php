<?php

namespace Chargens\Entity\Db;

use Application\Entity\Db\Traits\AnneeAwareTrait;
use Lieu\Entity\Db\StructureAwareTrait;
use OffreFormation\Entity\Db\Traits\GroupeTypeFormationAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * SeuilCharge
 */
class SeuilCharge implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use TypeInterventionAwareTrait;
    use GroupeTypeFormationAwareTrait;
    use StructureAwareTrait;
    use ScenarioAwareTrait;
    use AnneeAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $dedoublement;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @param int $id
     *
     * @return SeuilCharge
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }



    /**
     * @return int
     */
    public function getDedoublement()
    {
        return $this->dedoublement;
    }



    /**
     * @param int $dedoublement
     *
     * @return SeuilCharge
     */
    public function setDedoublement($dedoublement)
    {
        $this->dedoublement = $dedoublement;

        return $this;
    }



    public function calcPoids()
    {
        $poids = 0;

        if ($this->typeIntervention) $poids += 2;
        if ($this->groupeTypeFormation) $poids += 4;
        if ($this->structure) $poids += 8;
        if ($this->scenario) $poids += 16;

        return $poids;
    }

}
