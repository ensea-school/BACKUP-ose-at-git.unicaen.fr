<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\Intervenant;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Zend\Hydrator\HydratorInterface;

/**
 * Description of EditionForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class EditionForm extends AbstractForm
{

    public function init()
    {

        $hydrator = new IntervenantFormHydrator;
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl() );

        $this->add([
            'name'       => 'montant-indemnite-fc',
            'options'    => [
                'label' => "Montant annuel de la rémunération FC D714-60 (€) :",
            ],
            'attributes' => [
                'value'   => '0',
                'title'   => "Nombre d'heures",
            ],
            'type'       => 'Text',
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
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
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
            'montant-indemnite-fc' => StringFromFloat::run($object->getMontantIndemniteFc()),
        ];

        return $data;
    }
}