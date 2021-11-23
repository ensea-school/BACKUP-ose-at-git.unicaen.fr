<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Formule;

/**
 * Description of FormuleAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleAwareTrait
{
    /**
     * @var Formule
     */
    private $formule;





    /**
     * @param Formule $formule
     * @return self
     */
    public function setFormule( Formule $formule = null )
    {
        $this->formule = $formule;
        return $this;
    }



    /**
     * @return Formule
     */
    public function getFormule()
    {
        return $this->formule;
    }
}