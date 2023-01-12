<?php

namespace Enseignement\Form;

use Application\Constants;
use Application\Entity\Db\MotifNonPaiement;
use Application\Entity\Db\Periode;
use Application\Entity\Db\TypeIntervention;
use Application\Filter\DateTimeFromString;
use Application\Service\Traits\TagServiceAwareTrait;
use Enseignement\Entity\VolumeHoraireListe;
use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Enseignement\Hydrator\ListeFilterHydrator;
use Application\Service\Traits\MotifNonPaiementServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Laminas\Form\Element\DateTimeLocal;
use UnicaenApp\Util;
use Laminas\Form\Element\Hidden;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;


class VolumeHoraireSaisieCalendaireForm extends AbstractForm
{
    use MotifNonPaiementServiceAwareTrait;
    use TagServiceAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use PeriodeServiceAwareTrait;

    /**
     * @var boolean
     */
    protected $viewMNP = false;

    /**
     * @var boolean
     */
    protected $editMNP = false;

    /**
     * @var boolean
     */
    protected $viewTag = false;

    /**
     * @var boolean
     */
    protected $editTag = false;


    /**
     * @return MotifNonPaiement[]
     */
    protected function getMotifsNonPaiement()
    {
        $qb = $this->getServiceMotifNonPaiement()->finderByHistorique();

        return $this->getServiceMotifNonPaiement()->getList($qb);
    }


    /**
     * @return TypeIntervention[]
     */
    protected function getTypesIntervention()
    {
        $qb = $this->getServiceTypeIntervention()->finderByContext();
        $this->getServiceTypeIntervention()->finderByHistorique($qb);

        return $this->getServiceTypeIntervention()->getList($qb);
    }


    /**
     * @return Periode[]
     */
    protected function getPeriodes()
    {
        $qb = $this->getServicePeriode()->finderByHistorique();
        $this->getServicePeriode()->finderByEnseignement($qb);

        return $this->getServicePeriode()->getList($qb);
    }


    public function build()
    {
        $this->setAttributes([
            'action' => $this->getCurrentUrl(),
            'method' => 'post',
            'class'  => 'volume-horaire',
        ]);

        $hydrator = new SaisieCalendaireHydrator();
        $hydrator->setEntityManager($this->getEntityManager());
        $this->setHydrator($hydrator);

        $this->add([
            'type'    => DateTimeLocal::class,
            'name'    => 'horaire-debut',
            'options' => [
                'label' => 'Horaire de début',
            ],
        ]);

        $this->add([
            'type'    => DateTimeLocal::class,
            'name'    => 'horaire-fin',
            'options' => [
                'label' => 'Horaire de fin',
            ],
        ]);

        $this->add([
            'name'       => 'periode',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Période',
                'value_options' => Util::collectionAsOptions($this->getPeriodes()),
            ],
            'attributes' => [
                'value' => '',
                'title' => 'Période(semestre 1 ou 2)',
            ],
        ]);

        $this->add([
            'name'       => 'type-intervention',
            'type'       => 'Select',
            'options'    => [
                'label'         => 'Type d\'intervention',
                'value_options' => Util::collectionAsOptions($this->getTypesIntervention()),
            ],
            'attributes' => [
                'value' => '',
                'title' => 'Type d\'intervention (CM, TD, TP, etc.)',
            ],
        ]);

        $this->add([
            'name'       => 'heures',
            'type'       => 'Text',
            'options'    => [
                'label' => "Heures",
            ],
            'attributes' => [
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'volume-horaire volume-horaire-heures input-sm',
            ],
        ]);

        if ($this->canEditMNP()) {
            $this->add([
                'type'       => 'Select',
                'name'       => 'motif-non-paiement',
                'options'    => [
                    'label'                     => "Motif de non paiement :",
                    'empty_option'              => "Aucun motif : paiement prévu",
                    'value_options'             => Util::collectionAsOptions($this->getMotifsNonPaiement()),
                    'disable_inarray_validator' => true,
                ],
                'attributes' => [
                    'value' => "",
                    'title' => "Motif de non paiement",
                    'class' => 'volume-horaire volume-horaire-motif-non-paiement input-sm',
                ],
            ]);
        } else {
            $this->add(new Hidden('motif-non-paiement'));
        }


        //Gestion des tags
        if ($this->canEditTag()) {
            $this->add([
                'type'       => 'Select',
                'name'       => 'tag',
                'options'    => [
                    'label'                     => "Tag :",
                    'empty_option'              => "Aucun tag",
                    'value_options'             => Util::collectionAsOptions($this->getServiceTag()->getList()),
                    'disable_inarray_validator' => true,

                ],
                'attributes' => [
                    'value' => "",
                    'title' => "Tag",
                    'class' => 'volume-horaire volume-horaire-tag input-sm',
                ],
            ]);
        } else {
            $this->add(new Hidden('tag'));
        }

        $this->add(new Hidden('service'));
        $this->add(new Hidden('type-volume-horaire'));
        $this->add(new Hidden('ancien-motif-non-paiement'));
        $this->add(new Hidden('ancien-tag'));
        $this->add(new Hidden('ancien-horaire-debut'));
        $this->add(new Hidden('ancien-horaire-fin'));
        $this->add(new Hidden('ancien-periode'));
        $this->add(new Hidden('ancien-type-intervention'));
        $this->add(new Hidden('new'));

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
                'class' => 'volume-horaire volume-horaire-annuler btn btn-secondary fermer pop-ajax-hide',
            ],
        ]);
    }


    /**
     * @return bool
     */
    public function canViewMNP(): bool
    {
        return $this->viewMNP;
    }


    /**
     * @param bool $viewMNP
     *
     * @return SaisieCalendaire
     */
    public function setViewMNP(bool $viewMNP): self
    {
        $this->viewMNP = $viewMNP;

        return $this;
    }


    /**
     * @return bool
     */
    public function canEditMNP(): bool
    {
        return $this->editMNP;
    }


    /**
     * @return bool
     */
    public function canViewTag(): bool
    {
        return $this->viewTag;
    }


    /**
     * @param bool $viewTag
     *
     * @return Saisie
     */
    public function setViewTag(bool $viewTag): self
    {
        $this->viewTag = $viewTag;

        return $this;
    }


    /**
     * @return bool
     */
    public function canEditTag(): bool
    {
        return $this->editTag;
    }


    /**
     * @param bool $editTag
     *
     * @return Saisie
     */
    public function setEditTag(bool $editTag): self
    {
        $this->editTag = $editTag;

        return $this;
    }


    /**
     * @param bool $editMNP
     *
     * @return SaisieCalendaire
     */
    public function setEditMNP(bool $editMNP): self
    {
        $this->editMNP = $editMNP;

        return $this;
    }


    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'horaire-debut'             => [
                'required' => false,
            ],
            'horaire-fin'               => [
                'required'   => false,
                'validators' => [[
                    'name'    => 'Callback',
                    'options' => [
                        'messages' => [
                            \Laminas\Validator\Callback::INVALID_VALUE => 'L\'horaire de fin doit être ultérieur à l\'horaire de début',
                        ],
                        'callback' => function ($value, $context = []) {
                            if (!$context['horaire-debut'] && $context['horaire-fin']) return true; // pas d'horaires de saisis

                            $horaireDebut = DateTimeFromString::run($context['horaire-debut']);
                            $horaireFin = DateTimeFromString::run($context['horaire-fin']);
                            $deb = $horaireDebut->getTimestamp();
                            $fin = $horaireFin->getTimestamp();
                            $diff = $fin - $deb;

                            return $diff >= 0;
                        },
                    ],
                ]],
            ],
            'motif-non-paiement'        => [
                'required' => false,
            ],
            'ancien-motif-non-paiement' => [
                'required' => false,
            ],
            'tag'                       => [
                'required' => false,
            ],
            'ancien-tag'                => [
                'required' => false,
            ],
            'ancien-horaire-debut'      => [
                'required' => false,
            ],
            'ancien-horaire-fin'        => [
                'required' => false,
            ],
            'ancien-type-intervention'  => [
                'required' => false,
            ],
            'ancien-periode'            => [
                'required' => false,
            ],
            'periode'                   => [
                'required' => false,
            ],
            'new'                       => [
                'required' => false,
            ],
            'heures'                    => [
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
    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    private $data;


    private function getVal($key)
    {
        if (isset($this->data[$key])) {
            switch ($key) {
                case 'horaire-debut':
                case 'horaire-fin':
                    return DateTimeFromString::run($this->data[$key]);
                default:
                    return $this->data[$key];
            }
        } else {
            return null;
        }
    }


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param VolumeHoraireListe $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {


        $this->data = $data;

        $lfh = new ListeFilterHydrator();
        $lfh->setEntityManager($this->getEntityManager());


        $ancienHoraireDebut = $lfh->allToData(VolumeHoraireListe::FILTRE_HORAIRE_DEBUT, $this->getVal('ancien-horaire-debut'), $ho);
        $horaireDebut = $lfh->allToData(VolumeHoraireListe::FILTRE_HORAIRE_DEBUT, $this->getVal('horaire-debut'), $ho);
        $object->setHoraireDebut($ancienHoraireDebut != $horaireDebut ? $ancienHoraireDebut : $horaireDebut);

        $ancienHoraireFin = $lfh->allToData(VolumeHoraireListe::FILTRE_HORAIRE_FIN, $this->getVal('ancien-horaire-fin'), $ho);
        $horaireFin = $lfh->allToData(VolumeHoraireListe::FILTRE_HORAIRE_FIN, $this->getVal('horaire-fin'), $ho);
        $object->setHoraireFin($ancienHoraireFin != $horaireFin ? $ancienHoraireFin : $horaireFin);

        $ancienTypeIntervention = $lfh->allToData(VolumeHoraireListe::FILTRE_TYPE_INTERVENTION, $this->getVal('ancien-type-intervention'));
        $typeIntervention = $lfh->allToData(VolumeHoraireListe::FILTRE_TYPE_INTERVENTION, $this->getVal('type-intervention'));
        $object->setTypeIntervention($ancienTypeIntervention != $typeIntervention && $ancienTypeIntervention ? $ancienTypeIntervention : $typeIntervention);

        $ancienPeriode = $lfh->allToData(VolumeHoraireListe::FILTRE_PERIODE, $this->getVal('ancien-periode'));
        $periode = $lfh->allToData(VolumeHoraireListe::FILTRE_PERIODE, $this->getVal('periode'));
        $object->setPeriode($ancienPeriode != $periode && $ancienPeriode ? $ancienPeriode : $periode);

        $ancienMotifNonPaiement = $lfh->allToData(VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT, $this->getVal('ancien-motif-non-paiement'));
        $motifNonPaiement = $lfh->allToData(VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT, $this->getVal('motif-non-paiement'));
        $object->setMotifNonPaiement($ancienMotifNonPaiement != $motifNonPaiement && $ancienMotifNonPaiement ? $ancienMotifNonPaiement : $motifNonPaiement);

        $ancienTag = $lfh->allToData(VolumeHoraireListe::FILTRE_TAG, $this->getVal('ancien-tag'));
        $tag = $lfh->allToData(VolumeHoraireListe::FILTRE_TAG, $this->getVal('tag'));
        $object->setTag($ancienTag != $tag && $ancienTag ? $ancienTag : $tag);

        $heures = (float)$this->getVal('heures');
        $object->changeAll($horaireDebut, $horaireFin, $typeIntervention, $periode, $motifNonPaiement, $tag);
        $object->setHeures($heures);


        return $object;
    }


    /**
     * Extract values from an object
     *
     * @param VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $lfh = new ListeFilterHydrator();
        $lfh->setEntityManager($this->getEntityManager());
        $lfh->setFilters(VolumeHoraireListe::FILTRE_LIST);
        $data = $lfh->extractInts($object, true);

        /* Ajout des heures */
        $data['heures'] = $object->getHeures();

        /* Gestion des valeurs anciennes */
        $anciens = [
            'motif-non-paiement',
            'tag',
            'periode',
            'horaire-debut',
            'horaire-fin',
            'type-intervention',
        ];
        foreach ($anciens as $ancien) {
            if (isset($data[$ancien])) {
                $data['ancien-' . $ancien] = $data[$ancien];
            }
        }
        /*WIP TAG*/
        /* Conversion des dates en objets */
        if (isset($data['horaire-debut']) && $data['horaire-debut'] > 0) {
            $data['horaire-debut'] = (new \DateTime)->setTimestamp($data['horaire-debut']);
        }
        if (isset($data['horaire-fin']) && $data['horaire-fin'] > 0) {
            $data['horaire-fin'] = (new \DateTime)->setTimestamp($data['horaire-fin']);
        }

        return $data;
    }

}