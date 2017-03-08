<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\EtapeAwareTrait;
use Application\Entity\Db\Traits\ScenarioAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Traits\TypeFormationAwareTrait;
use Application\Entity\Db\Traits\TypeInterventionAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

/**
 * SeuilCharge
 */
class SeuilCharge implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use ScenarioAwareTrait;
    use StructureAwareTrait;
    use TypeFormationAwareTrait;
    use EtapeAwareTrait;
    use TypeInterventionAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $ouverture;

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
    public function getOuverture()
    {
        return $this->ouverture;
    }



    /**
     * @param int $ouverture
     *
     * @return SeuilCharge
     */
    public function setOuverture($ouverture)
    {
        $this->ouverture = $ouverture;

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

}
