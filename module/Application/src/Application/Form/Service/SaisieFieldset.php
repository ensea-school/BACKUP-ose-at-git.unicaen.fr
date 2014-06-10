<?php

namespace Application\Form\Service;

use Zend\Form\Fieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Acl\ComposanteDbRole;
use Application\Acl\IntervenantRole;
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

        if (! $this->getContextProvider()->getSelectedIdentityRole() instanceof IntervenantRole){
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

        if($role instanceof ComposanteDbRole) { // Si c'est un membre d'une composante
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