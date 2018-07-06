<?php

namespace Application\Form\VolumeHoraire;

use Application\Entity\VolumeHoraireListe;
use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Application\Service\Traits\MotifNonPaiementServiceAwareTrait;
use UnicaenApp\Util;
use Zend\Form\Element\Hidden;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Description of Saisie
 *
 */
class SaisieCalendaire extends AbstractForm
{
    use MotifNonPaiementServiceAwareTrait;

    /**
     * @var boolean
     */
    protected $viewMNP;

    /**
     * @var boolean
     */
    protected $editMNP;



    /**
     *
     */
    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());
        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'volume-horaire')
        ;
        $hydrator = new SaisieCalendaireHydrator();
        $this->setHydrator($hydrator);

        $this->add([
            'type'       => 'DateTime',
            'name'       => 'horaire-debut',
            'options'    => [
                'label'  => 'Horaire de début',
                'format' => Util::DATETIME_FORMAT,
            ],
        ]);

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

        $this->add([
            'name'       => 'motif-non-paiement',
            'options'    => [
                'label' => "Motif de non paiement :",
            ],
            'attributes' => [
                'value' => "",
                'title' => "Motif de non paiement",
                'class' => 'volume-horaire volume-horaire-motif-non-paiement input-sm',
            ],
            'type'       => 'Select',
        ]);

        $motifsNonPaiement = $this->getServiceMotifNonPaiement()->getList();
        foreach ($motifsNonPaiement as $id => $motifNonPaiement) {
            $motifsNonPaiement[$id] = (string)$motifNonPaiement;
        }
        $motifsNonPaiement[0] = 'Aucun motif : paiement prévu';
        $this->get('motif-non-paiement')->setValueOptions($motifsNonPaiement);

        $this->add(new Hidden('service'));
        $this->add(new Hidden('periode'));
        $this->add(new Hidden('type-volume-horaire'));
        $this->add(new Hidden('type-intervention'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'title' => "Enregistrer",
                'class' => 'volume-horaire volume-horaire-enregistrer btn btn-primary',
            ],
        ]);

        $this->add([
            'name'       => 'annuler',
            'type'       => 'Button',
            'options'    => [
                'label' => 'Fermer',
            ],
            'attributes' => [
                'title' => "Abandonner cette saisie",
                'class' => 'volume-horaire volume-horaire-annuler btn btn-default fermer',
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
            'motif-non-paiement' => [
                'required' => false,
            ],
            'periode'            => [
                'required' => false,
            ],
            'heures'             => [
                'required' => true,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
        ];
    }
}



/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieCalendaireHydrator implements HydratorInterface
{

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                  $data
     * @param  VolumeHoraireListe $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setHoraireDebut(\DateTime::createFromFormat(Util::DATETIME_FORMAT, $data['horaire-debut']) );

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'horaire-debut' => $object->getHoraireDebut(),
        ];

        return $data;
    }

}