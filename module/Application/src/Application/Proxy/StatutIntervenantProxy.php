<?php

namespace Application\Proxy;

use DoctrineModule\Form\Element\Proxy;

class StatutIntervenantProxy extends Proxy
{

    protected function loadValueOptions()
    {
        parent::loadValueOptions();

        foreach ($this->valueOptions as $key => $value) {
            $id     = $value['value'];
            $statut = $this->objects[$id];

            $this->valueOptions[$key]['attributes'] = [
                'class' => $statut->getCode(),
            ];
        }
    }

    protected function loadObjects()
    {
        parent::loadObjects();

        $statutIntervenant = [];
        foreach ($this->objects as $o) {
            if ($o->estNonHistorise()) {
                $statutIntervenant[$o->getId()] = $o;
            }
        }

        $this->objects = $statutIntervenant;
    }
}