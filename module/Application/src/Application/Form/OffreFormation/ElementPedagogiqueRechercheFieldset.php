<?php

namespace Application\Form\OffreFormation;

use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\NiveauEtape;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Traits\SessionContainerTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 */
class ElementPedagogiqueRechercheFieldset extends AbstractFieldset implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use ContextAwareTrait;
    use SessionContainerTrait;

    protected $structureName    = 'structure';

    protected $niveauName       = 'niveau';

    protected $etapeName        = 'etape';

    protected $structureEnabled = true;

    protected $niveauEnabled    = true;

    protected $etapeEnabled     = true;

    protected $relations        = [];

    /**
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;



    public function init()
    {
        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormElementPedagogiqueRechercheHydrator'))
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
                'id'    => 'element',
                'title' => "Saisissez 2 lettres au moins",
                'class' => 'element-pedagogique element-pedagogique-element input-sm',
            ],
            'type'       => 'UnicaenApp\Form\Element\SearchAndSelect',
        ]);

        $this->get('element')->setAutoCompleteSource($this->getUrl('of/element/search'));
    }



    public function populateOptions()
    {
        $data            = $this->getData();
        $this->relations = $data['relations'];
        $this->get('structure')->setValueOptions($data['structures']);
        $this->get('niveau')->setValueOptions($data['niveaux']);
        $this->get('etape')->setValueOptions($data['etapes']);
    }



    protected function getData()
    {
        if (!$this->getSessionContainer()->data) {

            $sql = "
            SELECT DISTINCT
              s.id structure_id,
              s.libelle_court structure_libelle,
              
              CASE WHEN gtf.pertinence_niveau = 0 THEN 'Autre' ELSE
                gtf.libelle_court || e.niveau 
              END niveau_id,
              CASE WHEN gtf.pertinence_niveau = 0 THEN 'Autre' ELSE
                gtf.libelle_court || e.niveau 
              END niveau_libelle,
              
              e.id etape_id,
              e.libelle etape_libelle
            FROM
              element_pedagogique ep
              JOIN etape e ON e.id = ep.etape_id
              JOIN type_formation tf ON tf.id = e.type_formation_id
              JOIN groupe_type_formation gtf ON gtf.id = tf.groupe_id
              JOIN structure s ON s.id = ep.structure_id
            WHERE
              1 = ose_divers.comprise_entre( ep.histo_creation, ep.histo_destruction )
              AND ep.annee_id = :annee
              ";

            $res = $query = $this->getEntityManager()->getConnection()->executeQuery(
                $sql,
                ['annee' => $this->getServiceContext()->getAnnee()->getId()]
            )->fetchAll();

            $result = [
                'structures' => [],
                'niveaux'    => [],
                'etapes'     => [],
                'relations'  => ['ALL' => ['ALL' => []]],
            ];
            foreach ($res as $e) {
                $structureId = $e['STRUCTURE_ID'];
                $structure   = $e['STRUCTURE_LIBELLE'];
                $niveauId    = $e['NIVEAU_ID'];
                $niveau      = $e['NIVEAU_LIBELLE'];
                $etapeId     = $e['ETAPE_ID'];
                $etape       = $e['ETAPE_LIBELLE'];

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
                $result['relations']['ALL']['ALL'][]            = $etapeId;
                $result['relations'][$structureId]['ALL'][]     = $etapeId;
                $result['relations']['ALL'][$niveauId][]        = $etapeId;
                $result['relations'][$structureId][$niveauId][] = $etapeId;
            }
            asort($result['structures']);
            asort($result['niveaux']);
            asort($result['etapes']);
            $this->getSessionContainer()->data = $result;
        }

        return $this->getSessionContainer()->data;
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
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
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