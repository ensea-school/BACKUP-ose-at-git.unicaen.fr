<?php

namespace Contrat\Form;

use Application\Form\AbstractForm;
use Contrat\Entity\Db\ContratAwareTrait;
use Laminas\Form\Element\Csrf;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 */
class ContratRetourForm extends AbstractForm
{
    use ContratAwareTrait;

    public function init2()
    {
        $this->setHydrator(new ContratRetourFormHydrator());
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', $this->getCurrentUrl());

        $contratToString = lcfirst($this->getContrat()->toString(true, true));

        $this->add([
            'name'       => 'dateRetourSigne',
            'type'       => 'Date',
            'options'    => [
                'label' => "Date de retour $contratToString signé",
            ],
            'attributes' => [
                'max' => date('Y-m-d'), // 🔒 interdit toute date future
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'valide'          => [
                'required' => false,
            ],
            'dateRetourSigne' => [
                'required' => false,
            ],
        ];
    }
}
