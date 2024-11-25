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

        $this->setAttribute('id', uniqid('fm'));

        $this->spec(SignatureFlow::class);

        $this->spec([
                        'label'  => ['input' => ['required' => true]],
                        'description' => ['type' => 'Textarea'],
                        'enabled'     => ['type' => 'Checkbox'],
                    ],
        );

        $this->build();

        $this->setLabels([
                             'enabled'     => 'Activé',
                             'label'       => 'Nom du circuit',
                             'description' => 'Description',
                         ]);

        $this->addSubmit();
    }

    public function getInputFilterSpecification()
    {

        return [
            'label'      => [
                'required'   => true,
            ],
        ];
    }


}