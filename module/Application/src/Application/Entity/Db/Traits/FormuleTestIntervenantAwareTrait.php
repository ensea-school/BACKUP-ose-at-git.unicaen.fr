<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleTestIntervenant;

/**
 * Description of FormuleTestIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleTestIntervenantAwareTrait
{
    /**
     * @var FormuleTestIntervenant
     */
    private $intervenantTest;





    /**
     * @param FormuleTestIntervenant $intervenantTest
     * @return self
     */
    public function setIntervenantTest( FormuleTestIntervenant $intervenantTest = null )
    {
        $this->intervenantTest = $intervenantTest;
        return $this;
    }



    /**
     * @return FormuleTestIntervenant
     */
    public function getIntervenantTest()
    {
        return $this->intervenantTest;
    }
}