<?php

namespace Enseignement\Form;

use Application\Entity\Db\Periode;
use Application\Filter\FloatFromString;
use Application\Form\AbstractForm;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Enseignement\Entity\VolumeHoraireListe;
use Enseignement\Hydrator\ListeFilterHydrator;
use Laminas\Form\Element\Hidden;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Entity\Db\TypeIntervention;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use Paiement\Entity\Db\MotifNonPaiement;
use Paiement\Service\MotifNonPaiementServiceAwareTrait;
use Service\Service\TagServiceAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;

class VolumeHoraireSaisieForm extends AbstractForm
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



    public function build ()
    {
        $this->setAttributes([
            'action' => $this->getCurrentUrl(),
            'method' => 'post',
            'class'  => 'volume-horaire',
        ]);

        $hydrator = new SaisieHydrator();
        $hydrator->setEntityManager($this->getEntityManager());
        $this->setHydrator($hydrator);

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
                    'label'         => "Motif de non paiement :",
                    'empty_option'  => "Aucun motif : paiement prévu",
                    'value_options' => Util::collectionAsOptions($this->getMotifsNonPaiement()),
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
                    'label'         => "Tag :",
                    'empty_option'  => "Aucun tag",
                    'value_options' => Util::collectionAsOptions($this->getServiceTag()->getListByDate()),
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
        $this->add(new Hidden('periode'));
        $this->add(new Hidden('type-intervention'));
        $this->add(new Hidden('type-volume-horaire'));
        $this->add(new Hidden('ancien-motif-non-paiement'));
        $this->add(new Hidden('ancien-tag'));


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
    public function canEditMNP (): bool
    {
        return $this->editMNP;
    }



    /**
     * @return MotifNonPaiement[]
     */
    protected function getMotifsNonPaiement ()
    {
        $qb = $this->getServiceMotifNonPaiement()->finderByHistorique();

        return $this->getServiceMotifNonPaiement()->getList($qb);
    }



    /**
     * @return bool
     */
    public function canEditTag (): bool
    {
        return $this->editTag;
    }



    /**
     * @return bool
     */
    public function canViewMNP (): bool
    {
        return $this->viewMNP;
    }



    /**
     * @param bool $viewMNP
     *
     * @return Saisie
     */
    public function setViewMNP (bool $viewMNP): self
    {
        $this->viewMNP = $viewMNP;

        return $this;
    }



    /**
     * @param bool $editMNP
     *
     * @return Saisie
     */
    public function setEditMNP (bool $editMNP): self
    {
        $this->editMNP = $editMNP;

        return $this;
    }



    /**
     * @return bool
     */
    public function canViewTag (): bool
    {
        return $this->viewTag;
    }



    /**
     * @param bool $viewTag
     *
     * @return Saisie
     */
    public function setViewTag (bool $viewTag): self
    {
        $this->viewTag = $viewTag;

        return $this;
    }



    /**
     * @param bool $editTag
     *
     * @return Saisie
     */
    public function setEditTag (bool $editTag): self
    {
        $this->editTag = $editTag;

        return $this;
    }



    public function disableMotifNonPaiement (): self
    {
        $this->get('motif-non-paiement')->setAttributes(['disabled' => 'disabled',
                                                         'title'    => 'Vous ne pouvez pas changer de motif de non paiement sur un service déjà validé']);

        return $this;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification ()
    {
        return [
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
            'periode'                   => [
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



    /**
     * @return TypeIntervention[]
     */
    protected function getTypesIntervention ()
    {
        $qb = $this->getServiceTypeIntervention()->finderByContext();
        $this->getServiceTypeIntervention()->finderByHistorique($qb);

        return $this->getServiceTypeIntervention()->getList($qb);
    }



    /**
     * @return Periode[]
     */
    protected function getPeriodes ()
    {
        $qb = $this->getServicePeriode()->finderByHistorique();
        $this->getServicePeriode()->finderByEnseignement($qb);

        return $this->getServicePeriode()->getList($qb);
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;

    /**
     * @var array
     */
    private $data;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param VolumeHoraireListe $object
     *
     * @return object
     */
    public function hydrate (array $data, $object)
    {


        $this->data = $data;

        $lfh = new ListeFilterHydrator();
        $lfh->setEntityManager($this->getEntityManager());

        $typeIntervention = $lfh->allToData(VolumeHoraireListe::FILTRE_TYPE_INTERVENTION, $this->getVal('type-intervention'));
        $object->setTypeIntervention($typeIntervention);

        $periode = $lfh->allToData(VolumeHoraireListe::FILTRE_PERIODE, $this->getVal('periode'));
        $object->setPeriode($periode);

        $ancienMotifNonPaiement = $lfh->allToData(VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT, $this->getVal('ancien-motif-non-paiement'));
        $motifNonPaiement       = $lfh->allToData(VolumeHoraireListe::FILTRE_MOTIF_NON_PAIEMENT, $this->getVal('motif-non-paiement'));

        $ancienTag = $lfh->allToData(VolumeHoraireListe::FILTRE_TAG, $this->getVal('ancien-tag'));
        $tag       = $lfh->allToData(VolumeHoraireListe::FILTRE_TAG, $this->getVal('tag'));


        $heures = (float)$this->getVal('heures');
        $object->setMotifNonPaiement($motifNonPaiement);
        $object->setTag($tag);
        $object->moveHeuresFromAncienMotifNonPaiementOrTag($heures, $ancienMotifNonPaiement, $ancienTag);


        return $object;
    }



    private function getVal ($key)
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return null;
        }
    }



    /**
     * Extract values from an object
     *
     * @param VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract ($object): array
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
        ];
        foreach ($anciens as $ancien) {
            if (isset($data[$ancien])) {
                $data['ancien-' . $ancien] = $data[$ancien];
            }
        }

        return $data;
    }

}