<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Zend\Form\Form;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of EditionForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EditionForm extends Form
{

    public function init()
    {

        $hydrator = new IntervenantFormHydrator;
        $this->setHydrator($hydrator);

        $this->add([
            'name'       => 'montant-indemnite-fc',
            'options'    => [
                'label' => "Montant de l'indemnité de formation continue (€) :",
            ],
            'attributes' => [
                'value'   => '0',
                'title'   => "Nombre d'heures",
                'step'    => 'any',
                'min'     => 0,
            ],
            'type'       => 'Number',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Appliquer',
                'class' => 'btn btn-primary',
            ],
        ]);
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
            'montant-indemnite-fc' => [
                'required' => false,
            ],
        ];
    }
}





class IntervenantFormHydrator implements HydratorInterface
{

    /**
     * @param  array       $data
     * @param  Intervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setMontantIndemniteFc($data['montant-indemnite-fc']);

        return $object;
    }



    /**
     * @param  Intervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                   => $object->getId(),
            'montant-indemnite-fc' => $object->getMontantIndemniteFc(),
        ];

        return $data;
    }
}