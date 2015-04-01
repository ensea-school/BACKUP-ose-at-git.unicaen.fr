<?php

namespace Application\Form\VolumeHoraireReferentiel;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;

/**
 * Description of Saisie
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Saisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     *
     */
    public function init()
    {
        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'volume-horaire')
//                ->setHydrator(new ClassMethods(false))
//                ->setInputFilter(new InputFilter())
//                ->setPreferFormInputFilter(false)
         ;

        $this->add([
            'name'       => 'heures',
            'options'    => [
                'label' => "Nombre d'heures :",
            ],
            'attributes' => [
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'volume-horaire volume-horaire-heures input-sm',
                'step'  => 'any',
                'min'   => 0,
            ],
            'type'       => 'Text',
        ]);

        $role = $this->getContextProvider()->getSelectedIdentityRole();

        $this->add( new Hidden('service-referentiel') );
        $this->add( new Hidden('type-volume-horaire') );

        $this->add([
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'title' => "Enregistrer",
                'class' => 'volume-horaire volume-horaire-enregistrer btn btn-primary'
            ],
        ]);

        $this->add([
            'name' => 'annuler',
            'type' => 'Button',
            'options' => [
                'label' => 'Fermer',
            ],
            'attributes' => [
                'title' => "Abandonner cette saisie",
                'class' => 'volume-horaire volume-horaire-annuler btn btn-default fermer'
            ],
        ]);
    }

    /* Associe une entity VolumeHoraireList au formulaire */
    public function bind( $object, $flags=17)
    {
        /* @var $object \Application\Entity\VolumeHoraireListe */

        $data = $object->filtersToArray();
        $data['service-referentiel'] = $object->getService()->getId();
        //$data['heures'] = str_replace('.',',',$object->getHeures());
        $data['heures'] = $object->getHeures();

        $this->setData($data);
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
            'heures' => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
        ];
    }

}