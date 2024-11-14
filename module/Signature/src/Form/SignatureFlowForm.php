<?php

namespace Signature\Form;

use Application\Form\AbstractForm;

use Signature\Hydrator\SignatureFlowHydrator;
use UnicaenSignature\Entity\Db\SignatureFlow;


/**
 * Description of SignatureFlowForm
 *
 */
class SignatureFlowForm extends AbstractForm
{

    public function init()
    {


        $this->setAttribute('id', uniqid('fm'));
        $this->setHydrator(new SignatureFlowHydrator());


        $this->spec(SignatureFlow::class);

        $this->spec([
                        'description' => ['type' => 'Textarea'],
                        'enabled'     => ['type' => 'Checkbox'],

                    ]);

        $this->build();

        $this->setLabels([
                             'enabled'     => 'Activé',
                             'label'       => 'Nom du circuit',
                             'description' => 'Description',
                         ]);

        $this->addSubmit();
    }

}