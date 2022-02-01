<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\ModeleContrat;

/**
 * Description of ModeleContratAwareTrait
 *
 * @author UnicaenCode
 */
trait ModeleContratAwareTrait
{
    protected ?ModeleContrat $modeleContrat = null;



    /**
     * @param ModeleContrat $modeleContrat
     *
     * @return self
     */
    public function setModeleContrat( ?ModeleContrat $modeleContrat )
    {
        $this->modeleContrat = $modeleContrat;

        return $this;
    }



    public function getModeleContrat(): ?ModeleContrat
    {
        return $this->modeleContrat;
    }
}