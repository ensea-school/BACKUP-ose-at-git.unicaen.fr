<?php

namespace Application\Form\OffreFormation;

use Zend\Form\Fieldset;

/**
 * Description of ElementPedagogiqueRechercheFieldset
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ElementPedagogiqueRechercheFieldset extends Fieldset implements \Zend\Form\ElementPrepareAwareInterface
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->add(array(
            'name'       => 'structure',
            'options'    => array(
                'label' => "Structure :",
                'empty_option' => "(Toutes)",
                'value_options' => $this->getStructures(),
            ),
            'attributes' => array(
                'title' => "Structure",
                'class' => 'element-pedagogique element-pedagogique-structure input-sm',
            ),
            'type' => 'Select',
        ));
        
        $this->add(array(
            'name'       => 'niveau',
            'options'    => array(
                'label' => "Niveau :",
                'empty_option' => "(Tous)",
                'value_options' => $this->getNiveaux(),
            ),
            'attributes' => array(
                'title' => "Niveau",
                'class' => 'element-pedagogique element-pedagogique-niveau input-sm',
            ),
            'type' => 'Select',
        ));

        $this->add(array(
            'name'       => 'etape',
            'options'    => array(
                'label' => "Étape :",
                'empty_option' => "(Toutes)",
                'value_options' => $this->getEtapes(),
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
                'label' => "Élément pédagogique :",
            ),
            'attributes' => array(
                'title' => "Saisissez 2 lettres au moins",
                'class' => 'element-pedagogique element-pedagogique-element input-sm',
            ),
            'type' => 'UnicaenApp\Form\Element\SearchAndSelect',
        ));
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
    protected $niveaux    = array();
    protected $etapes     = array();
    
    public function getNiveaux()
    {
        return $this->niveaux;
    }

    public function getEtapes()
    {
        return $this->etapes;
    }

    public function getStructures()
    {
        return $this->structures;
    }

    public function setNiveaux($niveaux)
    {
        $niveaux = array_combine(
                $tmp = array_map(function($v) { return $v['libelleCourt'] . $v['niveau']; }, $niveaux), 
                $tmp); 
        $this->niveaux = $niveaux;
        $this->get('niveau')->setValueOptions($this->getNiveaux());
        return $this;
    }

    public function setEtapes($etapes)
    {
        $this->etapes = \UnicaenApp\Util::collectionAsOptions($etapes);
        $this->get('etape')->setValueOptions($this->getEtapes());
        return $this;
    }

    public function setStructures($structures)
    {
        $this->structures = $structures;
        return $this;
    }
}