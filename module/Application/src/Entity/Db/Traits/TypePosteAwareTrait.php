<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\TypePoste;

/**
 * Description of TypePosteAwareTrait
 *
 * @author UnicaenCode
 */
trait TypePosteAwareTrait
{
    /**
     * @var TypePoste
     */
    private $typePoste;





    /**
     * @param TypePoste $typePoste
     * @return self
     */
    public function setTypePoste( TypePoste $typePoste = null )
    {
        $this->typePoste = $typePoste;
        return $this;
    }



    /**
     * @return TypePoste
     */
    public function getTypePoste()
    {
        return $this->typePoste;
    }
}