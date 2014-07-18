<?php

namespace Application\Entity\Db\Finder;

use Application\Entity\Db\Intervenant;

/**
 * Description of AbstractIntervenantFinder
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class AbstractIntervenantFinder extends AbstractFinder
{
    /**
     * 
     * @param int|Intervenant $intervenant
     * @return self
     */
    public function setIntervenant($intervenant)
    {
        if ($intervenant instanceof Intervenant) {
            $intervenant = $intervenant->getId();
        }
        
        $this
                ->andWhere('i.id = :id')
                ->setParameter('id', $intervenant);
        
        return $this;
    }
}