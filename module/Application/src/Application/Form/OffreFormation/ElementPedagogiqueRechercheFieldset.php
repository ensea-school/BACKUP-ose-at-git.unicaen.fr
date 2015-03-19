<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueRechercheFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    protected $structureName = 'structure';
    protected $niveauName    = 'niveau';
    protected $etapeName     = 'etape';

    protected $structureEnabled = true;
    protected $niveauEnabled    = true;
    protected $etapeEnabled     = true;
    protected $relations = [];

    /**
     *
     * @var QueryBuilder
     */
    protected $queryBuilder;


    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormElementPedagogiqueRechercheHydrator'))
             ->setAllowedObjectBindingClass('Application\Entity\Db\ElementPedagogique');

        $this->add(array(
            'name'       => $this->getStructureName(),
            'options'    => array(
                'label' => "Composante :",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes' => array(
                    'title' => "Structure gestionnaire de l'enseignement",
                ),
            ),
            'attributes' => array(
                'id'    => 'structure',
                'title' => "Structure gestionnaire de l'enseignement",
                'class' => 'element-pedagogique element-pedagogique-structure input-sm selectpicker',
                'data-width' => "100%",
                'data-live-search' => "true"
            ),
            'type' => 'Select',
        ));
        
        $this->add(array(
            'name'       => $this->getNiveauName(),
            'options'    => array(
                'label' => "Niveau :",
                'empty_option' => "(Tous)",
                'disable_inarray_validator' => true,
                'label_attributes' => array(
                    'title' => "Niveau",
                ),
            ),
            'attributes' => array(
                'id'    => 'niveau',
                'title' => "Niveau",
                'class' => 'element-pedagogique element-pedagogique-niveau input-sm selectpicker',
                'data-width' => "100%",
                'data-live-search' => "true"
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => $this->getEtapeName(),
            'options'    => array(
                'label' => "Formation :",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes' => array(
                    'title' => "Formation",
                ),
            ),
            'attributes' => array(
                'id'    => 'formation',
                'title' => "Formation",
                'class' => 'element-pedagogique element-pedagogique-etape input-sm selectpicker',
                'data-width' => "100%",
                'data-live-search' => "true"
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => 'element-liste',
            'options'    => array(
                'label' => "Enseignement :",
                'label_attributes' => array(
                    'title' => "Enseignement",
                ),
                'empty_option' => "(Tous)",
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'id'    => 'element-liste',
                'class' => 'element-pedagogique element-pedagogique-element selectpicker',
                'data-width' => "100%",
                'data-live-search' => "true",
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => 'element',
            'options'    => array(
                'label' => "Enseignement :",
                'label_attributes' => array(
                    'title' => "Enseignement",
                ),
            ),
            'attributes' => array(
                'id'    => 'element',
                'title' => "Saisissez 2 lettres au moins",
                'class' => 'element-pedagogique element-pedagogique-element input-sm',
            ),
            'type' => 'UnicaenApp\Form\Element\SearchAndSelect',
        ));

        $this->get('element')->setAutoCompleteSource( $url('of/element/default', array('action' => 'search')) );
    }

    public function populateOptions()
    {
        $data = $this->getData();
        $this->relations = $data['relations'];
        $this->get('structure')->setValueOptions( $data['structures'] );
        $this->get('niveau')->setValueOptions( $data['niveaux'] );
        $this->get('etape')->setValueOptions( $data['etapes'] );
    }

    protected function getData()
    {
        $qb = $this->getQueryBuilder();
        $entities = $qb->getQuery()->execute();
        $result = [
            'structures' => [],
            'niveaux'    => [],
            'etapes'     => [],
            'relations'  => ['ALL' => ['ALL' => []]]
        ];
        foreach( $entities as $entity ){
            if ($entity instanceof \Application\Entity\Db\Etape){
                $etape     = $entity;
                $niveau    = \Application\Entity\NiveauEtape::getInstanceFromEtape($etape);
                $structure = $etape->getStructure();

                $structureId = (string)$structure->getId();
                $niveauId = (string)$niveau->getId();
                $etapeId = (string)$etape->getId();

                if (! isset($result['structures'][$structureId])){
                    $result['structures'][$structureId] = (string)$structure;
                }
                if (! isset($result['niveaux'][$niveauId])){
                    $result['niveaux'][$niveauId] = (string)$niveau;
                }
                if (! isset($result['etapes'][$etapeId])){
                    $result['etapes'][$etapeId] = (string)$etape;
                }

                if (! isset($result['relations'][$structureId]['ALL'])){
                    $result['relations'][$structureId]['ALL'] = [];
                }
                if (! isset($result['relations']['ALL'][$niveauId])){
                    $result['relations']['ALL'][$niveauId] = [];
                }
                if (! isset( $result['relations'][$structureId][$niveauId] )){
                    $result['relations'][$structureId][$niveauId] = [];
                }
                $result['relations']['ALL']['ALL'][] = $etapeId;
                $result['relations'][$structureId]['ALL'][] = $etapeId;
                $result['relations']['ALL'][$niveauId][] = $etapeId;
                $result['relations'][$structureId][$niveauId][] = $etapeId;
            }
        }
        asort( $result['structures'] );
        asort( $result['niveaux'] );
        asort( $result['etapes'] );
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
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'structure' => array(
                'required' => false
            ),
            'niveau' => array(
                'required' => false
            ),
            'etape' => array(
                'required' => false
            ),
            'element-liste' => array(
                'required' => false
            ),
            'element' => array(
                'required' => false
            ),
        );
    }

    public function getQueryBuilder()
    {
        if (! $this->queryBuilder){
            $this->queryBuilder = $this->getServiceEtape()->initQuery()[0];

            $this->getServiceEtape()->join( $this->getServiceStructure(), $this->queryBuilder, 'structure', true );
            $this->getServiceEtape()->join( $this->getServiceTypeFormation(), $this->queryBuilder, 'typeFormation', true );
            $this->getServiceTypeFormation()->join( $this->getServiceGroupeTypeFormation(), $this->queryBuilder, 'groupe', true );

            $this->queryBuilder->andWhere($this->getServiceEtape()->getAlias().'.histoDestruction IS NULL');
            $this->getServiceEtape()->finderByNonOrphelines($this->queryBuilder);
        }
        return $this->queryBuilder;
    }

    /**
     * @return \Application\Service\Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return \Application\Service\Etape
     */
    protected function getServiceEtape()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationEtape');
    }

    /**
     * @return \Application\Service\ElementPedagogique
     */
    protected function getServiceElementPedagogique()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationElementPedagogique');
    }

    /**
     * @return \Application\Service\TypeFormation
     */
    protected function getServiceTypeFormation()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeFormation');
    }

    /**
     * @return \Application\Service\GroupeTypeFormation
     */
    protected function getServiceGroupeTypeFormation()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationGroupeTypeFormation');
    }
}