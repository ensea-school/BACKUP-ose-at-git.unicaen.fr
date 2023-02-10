<?php

namespace OffreFormation\Form;

use Application\Entity\Db\ElementPedagogique;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Service\Traits\ElementPedagogiqueServiceAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;


/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 */
class ElementPedagogiqueRechercheFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use SessionContainerTrait;
    use ElementPedagogiqueServiceAwareTrait;

    protected $structureName = 'structure';

    protected $niveauName = 'niveau';

    protected $etapeName = 'etape';

    protected $elementId = 'element';

    protected $structureEnabled = true;

    protected $niveauEnabled = true;

    protected $etapeEnabled = true;

    protected $relations = [];

    /**
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;


    public function init()
    {
        $hydrator = new ElementPedagogiqueRechercheHydrator;
        $hydrator->setServiceElementPedagogique($this->getServiceElementPedagogique());

        $this->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(ElementPedagogique::class);

        $this->add([
            'name'       => $this->getStructureName(),
            'options'    => [
                'label'                     => "Composante :",
                'empty_option'              => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
            ],
            'attributes' => [
                'id'               => 'structure',
                'title'            => "Structure gestionnaire de l'enseignement",
                'class'            => 'element-pedagogique element-pedagogique-structure input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => $this->getNiveauName(),
            'options'    => [
                'label'                     => "Niveau :",
                'empty_option'              => "(Tous)",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Niveau",
                ],
            ],
            'attributes' => [
                'id'               => 'niveau',
                'title'            => "Niveau",
                'class'            => 'element-pedagogique element-pedagogique-niveau input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => $this->getEtapeName(),
            'options'    => [
                'label'                     => "Formation :",
                'empty_option'              => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Formation",
                ],
            ],
            'attributes' => [
                'id'               => 'formation',
                'title'            => "Formation",
                'class'            => 'element-pedagogique element-pedagogique-etape input-sm selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'element-liste',
            'options'    => [
                'label'                     => "Enseignement :",
                'label_attributes'          => [
                    'title' => "Enseignement",
                ],
                'empty_option'              => "(Aucun enseignement sélectionné)",
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id'               => 'element-liste',
                'class'            => 'element-pedagogique element-pedagogique-element selectpicker',
                'data-width'       => "100%",
                'data-live-search' => "true",
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'element',
            'options'    => [
                'label'            => "Enseignement :",
                'label_attributes' => [
                    'title' => "Enseignement",
                ],
            ],
            'attributes' => [
                'id'    => $this->getElementId(),
                'title' => "Saisissez 2 lettres au moins",
                'class' => 'element-pedagogique element-pedagogique-element input-sm',
            ],
            'type'       => 'UnicaenApp\Form\Element\SearchAndSelect',
        ]);

        $this->get('element')->setAutoCompleteSource($this->getUrl('of/element/search'));
    }


    public function populateOptions()
    {
        $data = $this->getData();
        $this->relations = $data['relations'];
        $this->get('structure')->setValueOptions($data['structures']);
        $this->get('niveau')->setValueOptions($data['niveaux']);
        $this->get('etape')->setValueOptions($data['etapes']);
    }


    protected function getData()
    {
        $sql = "
            SELECT DISTINCT
              s.id structure_id,
              s.libelle_court structure_libelle,
              gtf.libelle_court || e.niveau niveau_id,
              gtf.libelle_court || e.niveau niveau_libelle,
              e.id etape_id,
              e.libelle etape_libelle,
              gtf.ordre
            FROM
              element_pedagogique ep
              JOIN chemin_pedagogique cp ON cp.element_pedagogique_id = ep.id AND cp.histo_destruction IS NULL
              JOIN etape e ON e.id = cp.etape_id
              JOIN type_formation tf ON tf.id = e.type_formation_id
              JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
              JOIN structure s ON s.id = ep.structure_id
            WHERE
              ep.histo_destruction IS NULL
              AND ep.annee_id = :annee
            ORDER BY gtf.ordre ASC
              ";

        $res = $this->getEntityManager()->getConnection()->fetchAllAssociative(
            $sql,
            ['annee' => $this->getServiceContext()->getAnnee()->getId()]
        );

        $result = [
            'structures' => [],
            'niveaux'    => [],
            'etapes'     => [],
            'relations'  => ['ALL' => ['ALL' => []]],
        ];
        foreach ($res as $e) {
            $structureId = $e['STRUCTURE_ID'];
            $structure = $e['STRUCTURE_LIBELLE'];
            $niveauId = $e['NIVEAU_ID'];
            $niveau = $e['NIVEAU_LIBELLE'];
            $etapeId = $e['ETAPE_ID'];
            $etape = $e['ETAPE_LIBELLE'];

            if (!isset($result['structures'][$structureId])) {
                $result['structures'][$structureId] = $structure;
            }
            if (!isset($result['niveaux'][$niveauId])) {
                $result['niveaux'][$niveauId] = $niveau;
            }
            if (!isset($result['etapes'][$etapeId])) {
                $result['etapes'][$etapeId] = $etape;
            }

            if (!isset($result['relations'][$structureId]['ALL'])) {
                $result['relations'][$structureId]['ALL'] = [];
            }
            if (!isset($result['relations']['ALL'][$niveauId])) {
                $result['relations']['ALL'][$niveauId] = [];
            }
            if (!isset($result['relations'][$structureId][$niveauId])) {
                $result['relations'][$structureId][$niveauId] = [];
            }
            $result['relations']['ALL']['ALL'][] = $etapeId;
            $result['relations'][$structureId]['ALL'][] = $etapeId;
            $result['relations']['ALL'][$niveauId][] = $etapeId;
            $result['relations'][$structureId][$niveauId][] = $etapeId;
        }
        asort($result['structures']);
        asort($result['etapes']);

        return $result;
    }


    public function getRelations()
    {
        return $this->relations;
    }


    public function getStructureName()
    {
        return $this->structureName;
    }


    public function getNiveauName()
    {
        return $this->niveauName;
    }


    public function getEtapeName()
    {
        return $this->etapeName;
    }


    public function getStructureEnabled()
    {
        return $this->structureEnabled;
    }


    public function getNiveauEnabled()
    {
        return $this->niveauEnabled;
    }


    public function getEtapeEnabled()
    {
        return $this->etapeEnabled;
    }


    public function setStructureEnabled($structureEnabled = true)
    {
        $this->structureEnabled = $structureEnabled;

        return $this;
    }


    public function setNiveauEnabled($niveauEnabled = true)
    {
        $this->niveauEnabled = $niveauEnabled;

        return $this;
    }


    public function setEtapeEnabled($etapeEnabled = true)
    {
        $this->etapeEnabled = $etapeEnabled;

        return $this;
    }


    /**
     * @return string
     */
    public function getElementId(): string
    {
        return $this->elementId;
    }


    /**
     * @param string $elementId
     *
     * @return ElementPedagogiqueRechercheFieldset
     */
    public function setElementId(string $elementId): ElementPedagogiqueRechercheFieldset
    {
        $this->elementId = $elementId;
        $this->get('element')->setAttribute('id', $elementId);

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
            'structure'     => [
                'required' => false,
            ],
            'niveau'        => [
                'required' => false,
            ],
            'etape'         => [
                'required' => false,
            ],
            'element-liste' => [
                'required' => false,
            ],
            'element'       => [
                'required' => false,
            ],
        ];
    }
}


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementPedagogiqueRechercheHydrator implements HydratorInterface
{
    use ElementPedagogiqueServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array $data
     * @param object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $id = (int)$data['element']['id'];
        if ($id) {
            $object = $this->getServiceElementPedagogique()->get($id);

            return $object;
        }

        return null;
    }


    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\ElementPedagogique $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [];

        $data['element'] = [
            'id'    => $object ? $object->getId() : null,
            'label' => $object ? $object->getLibelle() : null,
        ];

        $etape = $object ? $object->getEtape() : null;
        if ($etape) {
            $data['etape'] = $etape->getId();
        }
        $structure = $object ? $object->getStructure() : null;
        if ($structure) {
            $data['structure'] = $structure->getId();
        }

        return $data;
    }
}