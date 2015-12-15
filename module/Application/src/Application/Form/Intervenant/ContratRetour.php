<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Traits\ContratAwareTrait;
use Application\Form\AbstractForm;
use UnicaenApp\Hydrator\Strategy\DateStrategy;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Formulaire de saisie de la date de retour du contrat/avenant signé.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratRetour extends AbstractForm
{
    use ContratAwareTrait;

    public function init2()
    {
        $this->setHydrator(new ClassMethods(false));
        $this->setAttribute('method', 'POST');

        $contratToString = lcfirst($this->getContrat()->toString(true));

        $this->add([
            'name' => 'dateRetourSigne',
            'type'  => 'UnicaenApp\Form\Element\Date',
            'options' => [
                'label' => "Date de retour $contratToString signé",
            ],
            'attributes' => [
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
            ],
        ]);

        $this->getHydrator()->addStrategy('dateRetourSigne', new DateStrategy($this->get('dateRetourSigne')));

        return $this;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
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