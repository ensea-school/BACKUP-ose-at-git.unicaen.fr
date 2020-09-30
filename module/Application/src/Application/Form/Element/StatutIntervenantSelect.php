<?php

namespace Application\Form\Element;

use Application\Proxy\StatutIntervenantProxy;
use DoctrineORMModule\Form\Element\EntitySelect;

/**
 * Select d'entités StatutIntervenant, avec proxy dédié.
 */

class StatutIntervenantSelect extends EntitySelect
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $this->proxy = new StatutIntervenantProxy();
    }
}