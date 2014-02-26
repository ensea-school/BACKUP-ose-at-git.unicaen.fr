<?php

namespace Import\Model\Entity\Intervenant;

use Import\Model\Entity\Entity;

/**
 *
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class Affectation extends Entity {

    /**
     * ID intervenant
     *
     * @var integer
     */
    protected $intervenantId;

    /**
     * Structure
     *
     * @var integer
     */
    protected $structureId;

    /**
     * Si la structure est principale ou non
     *
     * @var boolean
     */
    protected $principale;

    /**
     * Si la structure est une structure de recherche
     *
     * @var boolean
     */
    protected $recherche;





    public function getIntervenantId()
    {
        return $this->intervenantId;
    }

    public function getStructureId()
    {
        return $this->structureId;
    }

    public function getPrincipale()
    {
        return $this->principale;
    }

    public function getRecherche()
    {
        return $this->recherche;
    }

    public function setIntervenantId($intervenantId)
    {
        $this->intervenantId = $intervenantId;
        return $this;
    }

    public function setStructureId($structureId)
    {
        $this->structureId = $structureId;
        return $this;
    }

    public function setPrincipale($principale)
    {
        $this->principale = $principale;
        return $this;
    }

    public function setRecherche($recherche)
    {
        $this->recherche = $recherche;
        return $this;
    }
}