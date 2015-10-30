<?php

namespace Application\Form\Intervenant;

use Zend\Form\Element\Csrf;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Application\Entity\Db\Traits\IntervenantAwareTrait;

/**
 * Formulaire de validation du référentiel d'un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ReferentielValidation extends Form implements InputFilterProviderInterface
{
    use IntervenantAwareTrait;

    public function init()
    {
        $this->setHydrator(new ClassMethods(false));

        $this->setAttribute('method', 'POST');
        $this->add([
            'name' => 'valide',
            'type'  => 'Checkbox',
            'options' => [
                'label' => "Cochez pour valider le référentiel",
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