<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueRechercheFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);

        $this->add(array(
            'name'       => $this->getStructureName(),
            'options'    => array(
                'label' => "Structure :",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'title' => "Structure",
                'class' => 'element-pedagogique element-pedagogique-structure input-sm',
            ),
            'type' => 'Select',
        ));
        
        $this->add(array(
            'name'       => $this->getNiveauName(),
            'options'    => array(
                'label' => "Niveau :",
                'empty_option' => "(Tous)",
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'title' => "Niveau",
                'class' => 'element-pedagogique element-pedagogique-niveau input-sm',
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => $this->getEtapeName(),
            'options'    => array(
                'label' => "Étape :",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
            ),
            'attributes' => array(
                'title' => "Étape",
                'class' => 'element-pedagogique element-pedagogique-etape input-sm',
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => 'element',
            'options'    => array(
                'label' => "Enseignement ou responsabilité :",
            ),
            'attributes' => array(
                'title' => "Saisissez 2 lettres au moins",
                'class' => 'element-pedagogique element-pedagogique-element input-sm',
            ),
            'type' => 'UnicaenApp\Form\Element\SearchAndSelect',
        ));
    }
    
    protected $structureName = 'structure';
    protected $niveauName    = 'niveau';
    protected $etapeName     = 'etape';
    
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
    
    protected $structureEnabled = true;
    protected $niveauEnabled    = true;
    protected $etapeEnabled     = true;
    
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
    
    protected $structuresSourceUrl;
    protected $niveauxSourceUrl;
    protected $etapesSourceUrl;
    protected $elementsSourceUrl;
    
    public function getStructuresSourceUrl()
    {
        return $this->structuresSourceUrl;
    }

    public function getNiveauxSourceUrl()
    {
        return $this->niveauxSourceUrl;
    }

    public function getEtapesSourceUrl()
    {
        return $this->etapesSourceUrl;
    }

    public function getElementsSourceUrl()
    {
        return $this->elementsSourceUrl;
    }

    public function setStructuresSourceUrl($structuresSourceUrl)
    {
        $this->structuresSourceUrl = $structuresSourceUrl;
        return $this;
    }

    public function setNiveauxSourceUrl($niveauxSourceUrl)
    {
        $this->niveauxSourceUrl = $niveauxSourceUrl;
        return $this;
    }

    public function setEtapesSourceUrl($etapesSourceUrl)
    {
        $this->etapesSourceUrl = $etapesSourceUrl;
        return $this;
    }

    public function setElementsSourceUrl($elementsSourceUrl)
    {
        $this->elementsSourceUrl = $elementsSourceUrl;
        $this->get('element')->setAutocompleteSource($elementsSourceUrl);
        return $this;
    }
    
    protected $structures = array();
    protected $etapes     = array();

    /**
     * Retournent les étapes possibles.
     * 
     * @return array
     */
    public function getEtapes()
    {
        return $this->etapes;
    }

    /**
     * Retournent les structures possibles.
     * 
     * @return array|Traversable
     */
    public function getStructures()
    {
        return $this->structures;
    }

    /**
     * Spécifie les étapes possibles.
     * 
     * @param array|Traversable $etapes
     * @return \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset
     */
    public function setEtapes($etapes)
    {
        $this->etapes = $etapes;
        $this->get('etape')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($etapes));
        return $this;
    }

    /**
     * Spécifie les structures possibles.
     * 
     * @param array|Traversable $structures
     * @return \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset
     */
    public function setStructures($structures)
    {
        $this->structures = $structures;
        $this->get('structure')->setValueOptions(\UnicaenApp\Util::collectionAsOptions($structures));
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
            'element' => array(
                'required' => false
            )
        );
    }

}