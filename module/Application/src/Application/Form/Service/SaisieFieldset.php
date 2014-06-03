<?php

namespace Application\Form\Service;

use Zend\Form\Fieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface, ContextProviderAwareInterface
{
    use ServiceLocatorAwareTrait;
    use ContextProviderAwareTrait;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;


    
    public function __construct($name = null, $options = array())
    {
        parent::__construct('service', $options);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->etablissement = $this->getContextProvider()->getGlobalContext()->getEtablissement();

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceSaisieFieldsetHydrator'))
              ->setAllowedObjectBindingClass('Application\Entity\Db\Service');

        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof \Application\Acl\IntervenantRole){
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant ->setRequired(true)
                         ->setSelectionRequired(true)
                         ->setAutocompleteSource(
                            $url('recherche', array('action' => 'intervenantFind'))
                         )
                         ->setLabel("Intervenant :")
                         ->setAttributes(array('title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"));
            $this->add($intervenant);
        }

        $this->add(array(
            'type'       => 'Radio',
            'name'       => 'interne-externe',
            'options'    => array(
                'label'  => "Service effectué :",
                'value_options' => array(
                    'service-interne' => 'en interne',
                    'service-externe' => 'hors '.$this->etablissement,
                ),
            ),
            'attributes' => array(
                'value' => 'service-interne'
            )
        ));

        /**
         * @todo : fourrer l'init des URL dans la classe ElementPedagogiqueRechercheFieldset
         */
        $queryTemplate = array('structure' => '__structure__', 'niveau' => '__niveau__', 'etape' => '__etape__');
        $urlStructures = $url('of/default',         array('action' => 'search-structures'), array('query' => $queryTemplate));
        $urlNiveaux    = $url('of/default',         array('action' => 'search-niveaux'),    array('query' => $queryTemplate));
        $urlEtapes     = $url('of/etape/default',   array('action' => 'search'),            array('query' => $queryTemplate));
        $urlElements   = $url('of/element/default', array('action' => 'search'),            array('query' => $queryTemplate));

        $fs = new \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset('element-pedagogique');
        $fs
                ->setStructuresSourceUrl($urlStructures)
                ->setNiveauxSourceUrl($urlNiveaux)
                ->setEtapesSourceUrl($urlEtapes)
                ->setElementsSourceUrl($urlElements)
        ;
        $this->add( $fs );

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement ->setRequired(true)
                       ->setSelectionRequired(true)
                       ->setAutocompleteSource(
                           $url('etablissement/recherche')
                       )
                       ->setLabel("Etablissement :")
                       ->setAttributes(array('title' => "Saisissez le libellé (2 lettres au moins)"));
        $this->add($etablissement);
    }

    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        $fs = $this->get('element-pedagogique');

        if($role instanceof \Application\Acl\ComposanteRole){ // Si c'est un membre d'une composante
            $fs->get('structure')->setValue( $role->getStructure()->getParenteNiv2()->getId() );
        }

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getContextProvider()->getLocalContext();

        if ($cl->getIntervenant()){
            $this->get('intervenant')->setValue(array(
                'id' => $cl->getIntervenant()->getSourceCode(),
                'label' => (string)$cl->getIntervenant()
            ));
        }
        if ($cl->getStructure()){
            $fs->get('structure')->setValue( $cl->getStructure()->getParenteNiv2()->getId() );
        }
        if ($cl->getEtape()){
            $fs->get('etape')->setValue( $cl->getEtape()->getId() );
        }
        if ($cl->getElementPedagogique()){
            $fs->get('element')->setValue( array(
                'id' => $cl->getElementPedagogique()->getId(),
                'label' => (string)$cl->getElementPedagogique()
            ));
        }
        $this->get('interne-externe')->setValue('service-interne');
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
     *
    public function bind($object, $flags = \Zend\Form\FormInterface::VALUES_NORMALIZED)
    {
        $data = array(
            'id'      => $object->getId(),
            'intervenant' => null,
            'elementPedagogique' => null,
            'etablissement' => null,
            'interne-externe' => 'service-interne'
        );

        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global /
        $contextProvider = $this->getServiceLocator()->getServiceLocator()->get('ApplicationContextProvider');
        /* @var $contextProvider \Application\Service\ContextProvider /
        $role = $contextProvider->getSelectedIdentityRole();

        if($role instanceof \Application\Acl\DbRole){ // Si c'est un RA
            $data['elementPedagogique']['structure'] = $role->getStructure()->getParenteNiv2()->getId();
        }

        /* Peuple le formulaire avec les valeurs par défaut issues du formulaire de recherche de services */
        /** @todo à refectoriser en utilisant un hydrateur de formulaire /
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

        /* Issues de l'objet transmis /
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
    }*/

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
            'element-pedagogique' => array(
                'required' => false
            ),
        );
    }
}