<?php

namespace Application\Form\Service;

use Zend\Form\Fieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Application\Entity\Db\Etablissement;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Zend\InputFilter\InputFilterProviderInterface;
use Application\Entity\Db\IntervenantExterieur;

/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends Fieldset implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait,
        \Application\Service\Traits\LocalContextAwareTrait,
        \Application\Service\Traits\EtapeAwareTrait,
        \Application\Service\Traits\NiveauEtapeAwareTrait,
        \Application\Service\Traits\StructureAwareTrait
    ;

    /**
     * etablissement par défaut
     *
     * @var Etablissement
     */
    protected $etablissement;



    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }

    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $this->etablissement = $this->getServiceContext()->getEtablissement();

        $this->setHydrator($this->getServiceLocator()->getServiceLocator()->get('FormServiceSaisieFieldsetHydrator'))
              ->setAllowedObjectBindingClass('Application\Entity\Db\Service');

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $identityRole = $this->getServiceContext()->getSelectedIdentityRole();

        if (! $identityRole instanceof IntervenantRole){
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant ->setRequired(true)
                         ->setSelectionRequired(true)
                         ->setAutocompleteSource(
                            $url('recherche', ['action' => 'intervenantFind'])
                         )
                         ->setLabel("Intervenant :")
                         ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
            $this->add($intervenant);
        }

        if (!($identityRole instanceof IntervenantRole && $identityRole->getIntervenant() instanceof IntervenantExterieur)){
            $this->add([
                'type'       => 'Radio',
                'name'       => 'interne-externe',
                'options'    => [
                    'label'  => "Enseignement effectué :",
                    'value_options' => [
                        'service-interne' => 'en interne',
                        'service-externe' => 'hors '.$this->etablissement,
                    ],
                ],
                'attributes' => [
                    'value' => 'service-interne'
                ]
            ]);
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
                       ->setAttributes(['title' => "Saisissez le libellé (2 lettres au moins)"]);
        $this->add($etablissement);
    }

    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs par défaut issues du contexte global */
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $fs = $this->get('element-pedagogique'); /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */
        if ($this->has('intervenant') && $this->getServiceLocalContext()->getIntervenant()){
            $this->get('intervenant')->setValue([
                'id' => $this->getServiceLocalContext()->getIntervenant()->getSourceCode(),
                'label' => (string)$this->getServiceLocalContext()->getIntervenant()
            ]);
        }

        if (! $role instanceof IntervenantRole){
            if ($this->getServiceLocalContext()->getStructure()){
                $structure = $this->getServiceLocalContext()->getStructure();
                $valueOptions = [$structure->getId() => (string) $structure];
                $fs->get('structure')->setValue($structure->getId());

            }
            if ($this->getServiceLocalContext()->getNiveau()){
                $fs->get('niveau')->setValue( $this->getServiceLocalContext()->getNiveau()->getId() );
            }
            if ($this->getServiceLocalContext()->getEtape()){
                $fs->get('etape')->setValue( $this->getServiceLocalContext()->getEtape()->getId() );
            }
        }
        if ($this->getServiceLocalContext()->getElementPedagogique()){
            $fs->get('element')->setValue( [
                'id' => $this->getServiceLocalContext()->getElementPedagogique()->getId(),
                'label' => (string)$this->getServiceLocalContext()->getElementPedagogique()
            ]);
        }
        if ($this->has('interne-externe')){
            $this->get('interne-externe')->setValue('service-interne');
        }

        // la structure de responsabilité du gestionnaire écrase celle du contexte local
        if ($role instanceof ComposanteRole) { // Si c'est un membre d'une composante
            $structure = $role->getStructure();
            $valueOptions = [$structure->getId() => (string) $structure];
            //    $this->getServiceStructure()->finderById($structure->getId(), $fs->getQueryBuilder());
            $fs->get('structure')->setValue($structure->getId());
        }
    }

    public function saveToContext()
    {
        /* Met à jour le contexte local en fonction des besoins... */
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $fs = $this->get('element-pedagogique'); /* @var $fs \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */

        /* Peuple le formulaire avec les valeurs issues du contexte local */

        if (! $role instanceof IntervenantRole){
            if ( $structureId = $fs->get('structure')->getValue() ){
                $this->getServiceLocalContext()->setStructure( $this->getServiceStructure()->get( $structureId ) );
            }else{
                $this->getServiceLocalContext()->setStructure( null );
            }
            if ( $niveauId = $fs->get('niveau')->getValue() ){
                $this->getServiceLocalContext()->setNiveau( $this->getServiceNiveauEtape()->get( $niveauId ) );
            }else{
                $this->getServiceLocalContext()->setNiveau( null );
            }
            if ( $etapeId = $fs->get('etape')->getValue() ){
                $this->getServiceLocalContext()->setEtape( $this->getServiceEtape()->get( $etapeId ) );
            }else{
                $this->getServiceLocalContext()->setEtape( null );
            }
        }
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.

     * @return array
     */
    public function getInputFilterSpecification(){
        return [
            'interne-externe' => [
                'required' => false
            ],
            'etablissement' => [
                'required' => false
            ],
            'element-pedagogique' => [
                'required' => false
            ],
        ];
    }
}