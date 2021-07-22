<?php

namespace Plafond\Form;

use Application\Form\AbstractForm;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of PlafondForm
 *
 * @author UnicaenCode
 */
class PlafondForm extends AbstractForm implements InputFilterProviderInterface
{

    public function init()
    {
        $hydrator = new PlafondFormHydrator;
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        /* Ajoutez vos éléments de formulaire ici */

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
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
            /* Filtres et validateurs */
        ];
    }

}





class PlafondFormHydrator implements HydratorInterface
{

    /**
     * @param array     $data
     * @param           $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* on peuple l'objet à partir du tableau de données */

        return $object;
    }



    /**
     * @param  $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            /* On peuple le tableau avec les données de l'objet */
        ];

        return $data;
    }
}