<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Traits\ContratAwareTrait;
use Application\Form\AbstractForm;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\ClassMethodsHydrator;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 */
class ContratRetour extends AbstractForm
{
    use ContratAwareTrait;

    public function init2()
    {
        $this->setHydrator(new ClassMethodsHydrator(false));
        $this->setAttribute('method', 'POST');
        $this->setAttribute('action', $this->getCurrentUrl());

        $contratToString = lcfirst($this->getContrat()->toString(true, true));

        $this->add([
            'name'       => 'dateRetourSigne',
            'type'       => 'UnicaenApp\Form\Element\Date',
            'options'    => [
                'label' => "Date de retour $contratToString signé",
            ],
            'attributes' => [
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

        $this->getHydrator()->addStrategy('dateRetourSigne', new DateStrategy($this->get('dateRetourSigne')));

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
            'valide' => [
                'required' => false,
            ],
        ];
    }
}