<?php

namespace Application\Entity\Db\Indicateur;

class Indicateur650 extends AbstractIndicateur
{
    /**
     * @var string
     */
    private $structuresConcernees;



    /**
     * @return string
     */
    public function getStructuresConcernees()
    {
        return $this->structuresConcernees;
    }



    /**
     * @param string $structuresConcernees
     *
     * @return Indicateur650
     */
    public function setStructuresConcernees($structuresConcernees)
    {
        $this->structuresConcernees = $structuresConcernees;

        return $this;
    }



    /**
     * Retourne les détails concernant l'indicateur
     *
     * @return string|null
     */
    public function getDetails()
    {
        $scs = explode('||', $this->getStructuresConcernees());
        $out = '<ul>';
        foreach( $scs as $sc ){
            $out .= "<li>$sc</li>";
        }
        $out .= '</ul>';

        return $out;
    }

}
