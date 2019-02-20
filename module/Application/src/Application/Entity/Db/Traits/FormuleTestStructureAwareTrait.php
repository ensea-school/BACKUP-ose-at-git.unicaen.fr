<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleTestStructure;

/**
 * Description of FormuleTestStructureAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleTestStructureAwareTrait
{
    /**
     * @var FormuleTestStructure
     */
    private $structureTest;





    /**
     * @param FormuleTestStructure $structureTest
     * @return self
     */
    public function setStructureTest( FormuleTestStructure $structureTest = null )
    {
        $this->structureTest = $structureTest;
        return $this;
    }



    /**
     * @return FormuleTestStructure
     */
    public function getStructureTest()
    {
        return $this->structureTest;
    }
}