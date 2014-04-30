<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\InputFilter\InputFilter;
use Application\Entity\Db\Etablissement;
use Zend\Form\Element\Hidden;
use Zend\Mvc\Controller\Plugin\Url;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form implements \Zend\InputFilter\InputFilterProviderInterface
{
    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;




    public function __construct( ServiceLocatorInterface $serviceLocator, Url $url, array $context=array() )
    {
        parent::__construct('service');

        $globalContext = $serviceLocator->get('ApplicationContextProvider')->getGlobalContext();
        
        $this->etablissement = $globalContext->getEtablissement();

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service')
                ->setHydrator(new ClassMethods(false))
                ->setInputFilter(new InputFilter())
         ;

        $id = new Hidden('id');
        $this->add($id);

        if (! isset($context['intervenant'])){
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant ->setRequired(true)
                         ->setSelectionRequired(true)
                         ->setAutocompleteSource(
                            $url->fromRoute('recherche', array('action' => 'intervenantFind'))
                         )
                         ->setLabel("Intervenant :")
                         ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
            $this->add($intervenant);
        }

        $interneExterne = new \Zend\Form\Element\Radio;
        $interneExterne->setLabel('Service effectué : ');
        $interneExterne->setName('interne-externe');
        $interneExterne->setValueOptions(array(
                     'service-interne' => 'en interne',
                     'service-externe' => 'hors '.$this->etablissement,
        ));
        $this->add($interneExterne);

        $queryTemplate = array('structure' => '__structure__', 'niveau' => '__niveau__', 'etape' => '__etape__');
        $urlStructures = $url->fromRoute('of/default', array('action' => 'search-structures'), array('query' => $queryTemplate));
        $urlNiveaux    = $url->fromRoute('of/default', array('action' => 'search-niveaux'), array('query' => $queryTemplate));
        $urlEtapes     = $url->fromRoute('of/default', array('action' => 'search-etapes'), array('query' => $queryTemplate));
        $urlElements   = $url->fromRoute('of/default', array('action' => 'search-element'), array('query' => $queryTemplate));

        $fs = new \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset('elementPedagogique');
        $fs
                ->setStructuresSourceUrl($urlStructures)
                ->setNiveauxSourceUrl($urlNiveaux)
                ->setEtapesSourceUrl($urlEtapes)
                ->setElementsSourceUrl($urlElements)
        ;
        $this->add($fs);

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement ->setRequired(true)
                       ->setSelectionRequired(true)
                       ->setAutocompleteSource(
                           $url->fromRoute('etablissement/recherche')
                       )
                       ->setLabel("Etablissement :")
                       ->setAttributes(array('title' => "Saisissez le libellé (2 lettres au moins)"));
        $this->add($etablissement);

        /**
         * Submit
         */
        $this->add(array(
            'name' => 'submit',
            'type'  => 'Submit',
            'attributes' => array(
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ),
        ));

        $this->setAttribute('action', $url->fromRoute(null, array(), array(), true));
    }

    /**
     * Bind an object to the form
     *
     * Ensures the object is populated with validated values.
     *
     * @param  object $object
     * @param  int $flags
     * @return mixed|void
     * @throws Exception\InvalidArgumentException
     */
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        $data = array(
            'id'      => $object->getId(),
        );
        if ($intervenant = $object->getIntervenant()){
            $data['intervenant'] = array( 'id' => $intervenant->getSourceCode(), 'label' => (string)$intervenant );
        }else{
            $data['intervenant'] = null;
        }
        if ($elementPedagogique = $object->getElementPedagogique()){
            $data['elementPedagogique']['element'] = array( 'id' => $elementPedagogique->getId(), 'label' => (string)$elementPedagogique );
        }else{
            $data['elementPedagogique'] = null;
        }
        if ($etablissement = $object->getEtablissement()){
            $data['etablissement'] = array( 'id' => $etablissement->getId(), 'label' => (string)$etablissement );
            $data['interne-externe'] = ($etablissement === $this->etablissement) ? 'service-interne' : 'service-externe';
        }else{
            $data['etablissement'] = null;
            $data['interne-externe'] = 'service-interne';
        }
        $this->setData($data);
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification(){
        return array(
            'interne-externe' => array(
                'required' => false
            ),
            'etablissement' => array(
                'required' => false
            ),
            'elementPedagogique' => array(
                'required' => false
            ),
        );
    }
}