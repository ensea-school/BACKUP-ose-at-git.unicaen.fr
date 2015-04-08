<?php

namespace Application\Form\VolumeHoraire;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Form\Element\Hidden;

/**
 * Description of Saisie
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class Saisie extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait
    ;

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

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($role instanceof \Application\Acl\Role) {

            $this->add([
                'name' => 'motif-non-paiement',
                'options'    => [
                    'label' => "Motif de non paiement :",
                ],
                'attributes' => [
                    'value' => "",
                    'title' => "Motif de non paiement",
                    'class' => 'volume-horaire volume-horaire-motif-non-paiement input-sm'
                ],
                'type' => 'Select'
            ]);

            $motifsNonPaiement = $this->getServiceLocator()->getServiceLocator()->get('ApplicationMotifNonPaiement')
                    ->getList();
            foreach( $motifsNonPaiement as $id => $motifNonPaiement ){
                $motifsNonPaiement[$id] = (string)$motifNonPaiement;
            }
            $motifsNonPaiement[0] = 'Aucun motif : paiement prévu';
            $this->get('motif-non-paiement')->setValueOptions( $motifsNonPaiement );
        }

        $this->add( new Hidden('service') );
        $this->add( new Hidden('periode') );
        $this->add( new Hidden('type-volume-horaire') );
        $this->add( new Hidden('type-intervention') );

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
        $data['service'] = $object->getService()->getId();
        //$data['heures'] = str_replace('.',',',$object->getHeures());
        $data['heures'] = $object->getHeures();

        if (! $this->getServiceLocator()->getServiceLocator()->get('applicationService')->canHaveMotifNonPaiement($object->getService())){
            $this->remove('motif-non-paiement');
        }else{
            $data['motif-non-paiement'] = $object->getMotifNonPaiement() ? $object->getMotifNonPaiement()->getId() : 0;
        }
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
            'motif-non-paiement' => [
                'required' => false
            ],
            'periode' => [
                'required' => false
            ],
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