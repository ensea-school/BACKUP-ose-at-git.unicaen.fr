<?php

namespace Mission\Form;

use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Form\AbstractForm;
use UnicaenApp\Util;


/**
 * Description of MissionSuiviForm
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionSuiviForm extends AbstractForm
{
    use IntervenantAwareTrait;

    public function build()
    {

        $this->setAttribute('action',$this->getCurrentUrl());

        $this->add([
            'name'    => 'mode',
            'type'    => 'Hidden',
        ]);

        $this->add([
            'name'       => 'mission',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Mission',
                'empty_option'  => '- Non renseignée -',
                'value_options' => Util::collectionAsOptions($this->getMissionsOptions()),
            ],
        ]);

        $this->add([
            'name'       => 'nocturne',
            'options'    => [
                'label' => 'Heures nocturnes',
            ],
            'type'       => 'Checkbox',
        ]);

        $this->get('mode')->setValue('mission');

        $this->addSubmit();
    }


    private function getMissionsOptions()
    {
        $missions = $this->getIntervenant()->getMissions();

        $options = [];
        foreach($missions as $mission){
            $options[$mission->getId()] = $mission->getTypeMission()->getLibelle();
        }

        return $options;
    }

}