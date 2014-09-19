<?php

namespace Application\Form\Service;

use Zend\Form\Fieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Entity\Db\IntervenantExterieur;

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

        $identityRole = $this->getContextProvider()->getSelectedIdentityRole();
        $contextIntervenant = $this->getContextProvider()->getGlobalContext()->getIntervenant();

        if (! $identityRole instanceof IntervenantRole){
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

        if (!($identityRole instanceof IntervenantRole && $contextIntervenant instanceof IntervenantExterieur)){
            $this->add(array(
                'type'       => 'Radio',
                'name'       => 'interne-externe',
                'options'    => array(
                    'label'  => "Enseignement effectué :",
                    'value_options' => array(
                        'service-interne' => 'en interne',
                        'service-externe' => 'hors '.$this->etablissement,
                    ),
                ),
                'attributes' => array(
                    'value' => 'service-interne'
                )
            ));
        }

        $fs = $this->getServiceLocator()->get('FormElementPedagogiqueRechercheFieldset');
        $fs->setName('element-pedagogique');
        $this->add( $fs );

        $etablissement = new SearchAndSelect('etablissement');
        $etablissement ->setRequired(true)
                       ->setSelectionRequired(true)
                       ->setAutocompleteSource(
                           $url('etablissement/recherche')
                       )
                       ->setLabel("Établissement :")
                       ->setAttributes(array('title' => "Saisissez le libellé (2 lettres au moins)"));
        $this->add($etablissement);
    }

    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        $fs = $this->get('element-pedagogique'); /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getContextProvider()->getLocalContext();

        if ($this->has('intervenant') && $cl->getIntervenant()){
            $this->get('intervenant')->setValue(array(
                'id' => $cl->getIntervenant()->getSourceCode(),
                'label' => (string)$cl->getIntervenant()
            ));
        }

        if (! $role instanceof IntervenantRole){
            if ($cl->getStructure()){
                $structure = $cl->getStructure();
                $valueOptions = array($structure->getId() => (string) $structure);
                $fs->get('structure')->setValue($structure->getId());

            }
            if ($cl->getNiveau()){
                $fs->get('niveau')->setValue( $cl->getNiveau()->getId() );
            }
            if ($cl->getEtape()){
                $fs->get('etape')->setValue( $cl->getEtape()->getId() );
            }
        }
        if ($cl->getElementPedagogique()){
            $fs->get('element')->setValue( array(
                'id' => $cl->getElementPedagogique()->getId(),
                'label' => (string)$cl->getElementPedagogique()
            ));
        }
        if ($this->has('interne-externe')){
            $this->get('interne-externe')->setValue('service-interne');
        }

        // la structure de responsabilité du gestionnaire écrase celle du contexte local
        if ($role instanceof ComposanteRole) { // Si c'est un membre d'une composante
            $structure = $role->getStructure();
            $valueOptions = array($structure->getId() => (string) $structure);
            //    $this->getServiceStructure()->finderById($structure->getId(), $fs->getQueryBuilder());
            $fs->get('structure')->setValue($structure->getId());
        }
    }

    public function saveToContext()
    {
        /* Met à jour le contexte local en fonction des besoins... */
        $role = $this->getContextProvider()->getSelectedIdentityRole();
        $fs = $this->get('element-pedagogique'); /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getContextProvider()->getLocalContext();

        if (! $role instanceof IntervenantRole){
            if ( $structureId = $fs->get('structure')->getValue() ){
                $cl->setStructure( $this->getServiceStructure()->get( $structureId ) );
            }else{
                $cl->setStructure( null );
            }
            if ( $niveauId = $fs->get('niveau')->getValue() ){
                $cl->setNiveau( $this->getServiceNiveauEtape()->get( $niveauId ) );
            }else{
                $cl->setNiveau( null );
            }
            if ( $etapeId = $fs->get('etape')->getValue() ){
                $cl->setEtape( $this->getServiceEtape()->get( $etapeId ) );
            }else{
                $cl->setEtape( null );
            }
        }
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.

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

    /**
     * @return \Application\Service\Structure
     */
    protected function getServiceStructure()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationStructure');
    }

    /**
     * @return \Application\Service\NiveauEtape
     */
    protected function getServiceNiveauEtape()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationNiveauEtape');
    }

    /**
     * @return \Application\Service\Etape
     */
    protected function getServiceEtape()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationEtape');
    }
}