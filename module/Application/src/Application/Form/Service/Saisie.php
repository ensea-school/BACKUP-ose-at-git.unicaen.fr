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
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 * Description of Saisie
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Saisie extends Form implements \Zend\InputFilter\InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;





    public function __construct( ServiceLocatorInterface $serviceLocator, Url $url )
    {
        parent::__construct('service');

        $this->setServiceLocator($serviceLocator->get('FormElementManager'));

        $context = $serviceLocator->get('ApplicationContextProvider')->getGlobalContext();
        $role    = $serviceLocator->get('ApplicationContextProvider')->getSelectedIdentityRole();

        $this->etablissement = $context->getEtablissement();

        $this   ->setAttribute('method', 'post')
                ->setAttribute('class', 'service')
                ->setHydrator(new ClassMethods(false))
                ->setInputFilter(new InputFilter())
         ;

        $id = new Hidden('id');
        $this->add($id);

        if (! $role instanceof \Application\Acl\IntervenantRole){
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
            'intervenant' => null,
            'elementPedagogique' => null,
            'etablissement' => null,
            'interne-externe' => 'service-interne'
        );

        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $contextProvider = $this->getServiceLocator()->getServiceLocator()->get('ApplicationContextProvider');
        /* @var $contextProvider \Application\Service\ContextProvider */
        $role = $contextProvider->getSelectedIdentityRole();

        if($role instanceof \Application\Acl\DbRole){ // Si c'est un RA
            $data['elementPedagogique']['structure'] = $role->getStructure()->getParenteNiv2()->getId();
        }

        /* Peuple le formulaire avec les valeurs par défaut issues du formulaire de recherche de services */
        /** @todo à refectoriser en utilisant un hydrateur de formulaire */
        $rechercheForm = $this->getServiceLocator()->get('ServiceRecherche');
        $filters = $rechercheForm->hydrateFromSession();

        if (isset($filters->intervenant) && $filters->intervenant){
            $data['intervenant'] = array(
                'id' => $filters->intervenant->getSourceCode(),
                'label' => (string)$filters->intervenant
            );
        }
        if (isset($filters->structureEns) && $filters->structureEns){
            $data['elementPedagogique']['structure'] = $filters->structureEns->getId();
        }
        if (isset($filters->etape) && $filters->etape){
            $data['elementPedagogique']['etape'] = $filters->etape->getId();
        }
        if (isset($filters->elementPedagogique) && $filters->elementPedagogique){
            $data['elementPedagogique']['element'] = array(
                'id' => $filters->elementPedagogique->getId(),
                'label' => (string)$filters->elementPedagogique
            );
        }

        /* Issues de l'objet transmis */
        if ($intervenant = $object->getIntervenant()){
            $data['intervenant'] = array( 'id' => $intervenant->getSourceCode(), 'label' => (string)$intervenant );
        }
        if ($elementPedagogique = $object->getElementPedagogique()){
            $data['elementPedagogique']['element'] = array( 'id' => $elementPedagogique->getId(), 'label' => (string)$elementPedagogique );
        }
        if ($etablissement = $object->getEtablissement()){
            $data['etablissement'] = array( 'id' => $etablissement->getId(), 'label' => (string)$etablissement );
            $data['interne-externe'] = ($etablissement === $this->etablissement) ? 'service-interne' : 'service-externe';
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