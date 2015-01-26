<?php

namespace Application\Entity\Db;

/**
 * AdresseIntervenant
 */
class AdresseIntervenantPrinc extends AdresseIntervenant
{
    /**
     * @var string
     */
    protected $toString;
    
    /**
     * 
     * @return string
     */
    public function getToString()
    {
        return $this->toString;
    }
}