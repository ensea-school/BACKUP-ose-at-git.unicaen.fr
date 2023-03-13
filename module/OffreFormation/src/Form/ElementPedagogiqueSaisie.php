<?php

namespace OffreFormation\Form;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\PeriodeServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Service\Traits\DisciplineServiceAwareTrait;
use OffreFormation\Service\Traits\EtapeServiceAwareTrait;

/**
 * Description of ElementPedagogiqueSaisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSaisie extends AbstractForm
{
    use LocalContextServiceAwareTrait;
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DisciplineServiceAwareTrait;


    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        /* Définition de l'hydrateur */
        $hydrator = new ElementPedagogiqueSaisieHydrator();
        $hydrator->setServiceEtape($this->getServiceEtape());
        $hydrator->setServicePeriode($this->getServicePeriode());
        $hydrator->setServiceStructure($this->getServiceStructure());
        $this->setHydrator($hydrator);

        $this->setAttribute('class', 'element-pedagogique-saisie');
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'       => 'etape',
            'options'    => [
                'label' => 'Formation',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => 'Code',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'       => 'discipline',
            'options'    => [
                'label' => 'Discipline',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => 'Libellé',
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'    => 'periode',
            'options' => [
                'label' => 'Période',
            ],
            'type'    => 'Select',
        ]);

        $this->add([
            'name'       => 'taux-foad',
            'options'    => [
                'label' => 'FOAD',
            ],
            'attributes' => [
                'title' => "Formation ouverte à distance",
            ],
            'type'       => 'Checkbox',
        ]);

        $this->add([
            'name'       => 'taux-fc',
            'options'    => [
                'label' => 'FC (%)',
            ],
            'attributes' => [
                'title' => "Taux de formation continue",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'taux-fi',
            'options'    => [
                'label' => 'FI (%)',
            ],
            'attributes' => [
                'title' => "Taux de formation initiale",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'taux-fa',
            'options'    => [
                'label' => 'FA (%)',
            ],
            'attributes' => [
                'title' => "Taux de formation en apprentissage",
                'class' => 'input-sm',
                'step'  => 'any',
                'min'   => 0,
                'max'   => 1,
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => 'Structure',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary enregistrer',
            ],
        ]);

        $localContext = $this->getServiceLocalContext();

        // init étape
        $qb = $this->getServiceEtape()->finderByContext();
        $this->get('etape')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceEtape()->getList($qb)));
        if (($etape = $localContext->getEtape())) {
            $this->get('etape')->setValue($etape->getId());
        }

        $this->get('discipline')->setValueOptions(
            \UnicaenApp\Util::collectionAsOptions(
                $this->getServiceDiscipline()->getList(
                    $this->getServiceDiscipline()->finderByHistorique()
                )
            )
        );

        // peuplement liste des périodes
        $this->get('periode')
            ->setEmptyOption("")
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServicePeriode()->getEnseignement()));

        // peuplement liste des structures
        $serviceStructure = $this->getServiceStructure();
        $qb               = $serviceStructure->finderByEnseignement();
        $this->get('structure')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStructure->getList($qb)));
        if ($structure = $localContext->getStructure()) {
            $this->get('structure')->setValue($structure->getId());
        }
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
            'taux-foad'  => [
                'required' => true,
            ],
            'taux-fc'    => [
                'required' => true,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
            'taux-fi'    => [
                'required' => true,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
            'taux-fa'    => [
                'required' => true,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ],
            'code'       => [
                'required' => true,
            ],
            'discipline' => [
                'required' => true,
            ],
            'libelle'    => [
                'required' => true,
            ],
            'periode'    => [
                'required' => false,
            ],
            'etape'      => [
                'required' => true,
            ],
            'structure'  => [
                'required' => true,
            ],
        ];
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueSaisieHydrator implements HydratorInterface
{
    use EtapeServiceAwareTrait;
    use PeriodeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DisciplineServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                            $data
     * @param \OffreFormation\Entity\Db\ElementPedagogique $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setDiscipline($this->getServiceDiscipline()->get($data['discipline']));
        $object->setSourceCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setEtape($this->getServiceEtape()->get($data['etape']));
        $object->setPeriode($this->getServicePeriode()->get($data['periode']));
        $object->setTauxFoad((float)$data['taux-foad']);
        $object->setTauxFc(FloatFromString::run($data['taux-fc']) / 100);
        $object->setTauxFi(FloatFromString::run($data['taux-fi']) / 100);
        $object->setTauxFa(FloatFromString::run($data['taux-fa']) / 100);
        $object->setFc(FloatFromString::run($data['taux-fc']) > 0);
        $object->setFi(FloatFromString::run($data['taux-fi']) > 0);
        $object->setFa(FloatFromString::run($data['taux-fa']) > 0);
        $object->setStructure($this->getServiceStructure()->get($data['structure']));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'etape'      => ($e = $object->getEtape()) ? $e->getId() : null,
            'code'       => $object->getCode(),
            'discipline' => $object->getDiscipline() ? $object->getDiscipline()->getId() : null,
            'libelle'    => $object->getLibelle(),
            'id'         => $object->getId(),
            'periode'    => ($p = $object->getPeriode()) ? $p->getId() : null,
            'taux-foad'  => $object->getTauxFoad(),
            'structure'  => ($s = $object->getStructure()) ? $s->getId() : null,
            'taux-fc'    => StringFromFloat::run($object->getTauxFc() * 100),
            'taux-fi'    => StringFromFloat::run($object->getTauxFi() * 100),
            'taux-fa'    => StringFromFloat::run($object->getTauxFa() * 100),
        ];

        return $data;
    }
}