<?php

namespace Application\Validator;

use Zend\Validator\AbstractValidator;
use Application\Entity\Db\StatutIntervenant;

class StatutIntervenantValidator extends AbstractValidator
{
    const MSG_INTERDIT = 'msgInterdit';
    
    protected $messageTemplates = array(
        self::MSG_INTERDIT => "Ce statut n'est pas accepté.",
    );
    
    private $statutsInterdits = [];
    
    public function isValid($value, $context = null)
    {
        $interdits    = (array) $this->getStatutsInterdits();
        $interditsIds = array_map(function($v) { return $v->getId(); }, $interdits);
        
        if ($interditsIds && in_array((int) $value, $interditsIds)) {
            $this->error(self::MSG_INTERDIT);
            return false;
        }
        
        return true;
    }

    /**
     * @return StatutIntervenant[]
     */
    function getStatutsInterdits()
    {
        return $this->statutsInterdits;
    }

    /**
     * 
     * @param StatutIntervenant[] $statutsInterdits
     * @return self
     */
    function setStatutsInterdits($statutsInterdits)
    {
        $this->statutsInterdits = (array) $statutsInterdits;
        return $this;
    }
}