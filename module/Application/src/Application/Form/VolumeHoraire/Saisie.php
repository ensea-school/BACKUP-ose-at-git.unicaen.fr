<?php

namespace Application\Form\VolumeHoraire;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Application\Service\Traits\MotifNonPaiementAwareTrait;
use Application\Service\Traits\ServiceAwareTrait;
use Zend\Form\Element\Hidden;
use Application\Service\Traits\ContextAwareTrait;

/**
 * Description of Saisie
 *
 */
class Saisie extends AbstractForm
{
    use ContextAwareTrait;
    use MotifNonPaiementAwareTrait;
    use ServiceAwareTrait;

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
            'type'       => 'Text',
            'options'    => [
                'label' => "Nombre d'heures :",
            ],
            'attributes' => [
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'volume-horaire volume-horaire-heures input-sm',
            ],
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

            $motifsNonPaiement = $this->getServiceMotifNonPaiement()->getList();
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
        $data['heures'] = StringFromFloat::run($object->getHeures());

        if (! $this->getServiceService()->canHaveMotifNonPaiement($object->getService())){
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
                    ['name' => FloatFromString::class],
                ],
            ],
        ];
    }

}