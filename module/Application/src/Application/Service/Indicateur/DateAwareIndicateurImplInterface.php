<?php

namespace Application\Service\Indicateur;

use DateTime;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface DateAwareIndicateurImplInterface
{
    /**
     * @param DateTime $date
     */
    public function setDate(DateTime $date = null);
    
    /**
     * @return DateTime
     */
    public function getDate();
}