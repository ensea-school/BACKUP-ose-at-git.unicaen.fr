<?php

namespace Application\Form\VolumeHoraire;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Application\Hydrator\VolumeHoraire\ListeFilterHydrator;
use Application\Service\Traits\MotifNonPaiementServiceAwareTrait;
use Zend\Form\Element\Hidden;

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

        $this->add([
            'type'       => 'UnicaenApp\Form\Element\DateTime',
            'name'       => 'horaire-debut',
            'options'    => [
                'label'  => 'Horaire de début',
            ],
        ]);
        $this->get('horaire-debut')->setIncludeTime(true);

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



    /* Associe une entity VolumeHoraireList au formulaire */
    public function bind($object, $flags = 17)
    {
        /* @var $object \Application\Entity\VolumeHoraireListe */
        $vhlph = new ListeFilterHydrator();

        $data            = $vhlph->extract($object);
        $data['service'] = $object->getService()->getId();
        $data['heures']  = StringFromFloat::run($object->getHeures(), false);

        if (!$this->getViewMNP()) {
            $this->remove('motif-non-paiement');
        } else {
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



    /**
     * @return boolean
     */
    public function getViewMNP()
    {
        return $this->viewMNP;
    }



    /**
     * @param boolean $viewMNP
     *
     * @return Saisie
     */
    public function setViewMNP($viewMNP)
    {
        $this->viewMNP = $viewMNP;

        return $this;
    }



    /**
     * @return boolean
     */
    public function getEditMNP()
    {
        return $this->editMNP;
    }



    /**
     * @param boolean $editMNP
     *
     * @return Saisie
     */
    public function setEditMNP($editMNP)
    {
        $this->editMNP = $editMNP;

        if (!$editMNP && $this->has('motif-non-paiement')) {
            $this->remove('motif-non-paiement');
        }

        return $this;
    }
}