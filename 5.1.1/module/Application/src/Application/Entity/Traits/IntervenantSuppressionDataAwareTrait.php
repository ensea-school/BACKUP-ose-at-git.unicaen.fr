<?php

namespace Application\Entity\Traits;

use Application\Entity\IntervenantSuppressionData;

/**
 * Description of IntervenantSuppressionDataAwareTrait
 *
 */
trait IntervenantSuppressionDataAwareTrait
{
    /**
     * @var IntervenantSuppressionData
     */
    private $intervenantSuppressionData;





    /**
     * @param IntervenantSuppressionData $intervenantSuppressionData
     * @return self
     */
    public function setIntervenantSuppressionData( IntervenantSuppressionData $intervenantSuppressionData = null )
    {
        $this->intervenantSuppressionData = $intervenantSuppressionData;
        return $this;
    }



    /**
     * @return IntervenantSuppressionData
     */
    public function getIntervenantSuppressionData()
    {
        return $this->intervenantSuppressionData;
    }
}