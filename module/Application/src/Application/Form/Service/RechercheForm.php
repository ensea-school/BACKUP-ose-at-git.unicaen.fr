<?php

namespace Application\Form\Service;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Hidden;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\ElementInterface;

/**
 * Description of RechercheForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheForm extends Form implements InputFilterProviderInterface, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     *
     * @var string
     */
    private $id;

    /**
     * Liste des boutons d'actions
     *
     * @var ElementInterface
     */
    protected $actionButtons = [];




    /**
     * Retourne un identifiant unique de formulaire.
     * Une fois ce dernier initialisé, il ne change plus pour l'instance en cours
     *
     * @return string
     */
    public function getId()
    {
        if (null === $this->id) $this->id = uniqid();
        return $this->id;
    }

    /**
     * Ajoute un bouton d'action au formulaire
     *
     * @param string $name
     * @param string $label
     * @param string $actionUrl
     * @param boolean $primary
     * @param array $attributes
     * @return self
     */
    public function addActionButton($name, $label, $actionUrl, $primary=false, array $attributes=[])
    {
        if (! isset($attributes['type']))    $attributes['type'] = 'submit';
        if (! isset($attributes['class']))   $attributes['class'] = 'btn '.($primary ? 'btn-primary' : 'btn-default');
        if (! isset($attributes['onclick'])) $attributes['onclick'] = '$("#'.$this->getId().'").attr("action", "'.$actionUrl.'");';

        $this->add([
            'name'       => $name,
            'type'       => 'Button',
            'options'    => ['label' => $label],
            'attributes' => $attributes,
        ]);
        $this->actionButtons[$name] = $this->get($name);
        
        return $this;
    }

    /**
     * Retourne tous les boutons d'action
     *
     * @return \Zend\Form\ElementInterface[]
     */
    public function getActionButtons()
    {
        return $this->actionButtons;
    }

    /**
     * 
     */
    public function init()
    {
        $url = $this->getServiceLocator()->getServiceLocator()->get('viewhelpermanager')->get('url');
        /* @var $url Zend\View\Helper\Url */

        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('ServiceRechercheFormHydrator');
        /* @var $hydrator RechercheHydrator */
        $this->setHydrator( $hydrator )
             ->setAllowedObjectBindingClass('Application\Entity\Service\Recherche');

        $this   ->setAttribute('method', 'get')
                ->setAttribute('class', 'service-recherche')
                ->setAttribute('id', $this->getId());


        $typeIntervenant = new \Zend\Form\Element\Radio('type-intervenant');
        $typeIntervenant
                ->setValueOptions(array(
                    '' => "Peu importe",
                    $this->getServiceTypeIntervenant()->getPermanent()->getId() => "Permanent",
                    $this->getServiceTypeIntervenant()->getExterieur()->getId() => "Vacataire"))
                ->setValue('')
                ->setLabel("Statut :");
        $this->add($typeIntervenant);

        $structures = $this->getServiceStructure()->getList(
                          $this->getServiceStructure()->finderByEnseignement(
                              $this->getServiceStructure()->finderByNiveau(2)
                          )
                      );
        $this->add(array(
            'name'       => 'structure-aff',
            'options'    => array(
                'label' => "Structure d'affectation:",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes' => array(
                    'title' => "Structure gestionnaire de l'enseignement",
                ),
            ),
            'attributes' => array(
                'title' => "Structure gestionnaire de l'enseignement",
                'class' => 'input-sm',
            ),
            'type' => 'Select',
        ));
        $this->get('structure-aff')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $structures ) );

        $intervenant = new SearchAndSelect('intervenant');
        $intervenant
                ->setAutocompleteSource($url(
                    'recherche',
                    array('action' => 'intervenantFind'),
                    array('query' => array('having-services' => 1))
                ) )
                ->setLabel('Intervenant :');
        $this->add($intervenant);


        $elementPedagogique = $this->getServiceLocator()->get('FormElementPedagogiqueRechercheFieldset');
        /* @var $elementPedagogique \Application\Form\OffreFormation\ElementPedagogiqueRechercheFieldset */
        $elementPedagogique->setName('element-pedagogique');
        $elementPedagogique->setLabel('Enseignement :');
        $elementPedagogique->setNiveauEnabled(false);
        $this->add( $elementPedagogique );


        $typeVolumeHoraire = new Select('type-volume-horaire');
        $typeVolumeHoraire->setLabel('Type :');
        $typeVolumeHoraire->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $this->getServiceTypeVolumeHoraire()->getList() ) );
        $typeVolumeHoraire->setValue( $this->getServiceTypeVolumeHoraire()->getPrevu()->getId() );
        $this->add($typeVolumeHoraire);


        $etatVolumeHoraire = new Select('etat-volume-horaire');
        $etatVolumeHoraire->setLabel('État :');
        $etatVolumeHoraire->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $this->getServiceEtatVolumeHoraire()->getList() ) );
        $typeVolumeHoraire->setValue( $this->getServiceEtatVolumeHoraire()->getSaisi()->getId() );
        $this->add($etatVolumeHoraire);


        $action = new Hidden('action');
        $action->setValue('afficher');
        $this->add($action);


        $this->addActionButton('submit-resume' , 'Afficher (résumé)' , $url('service/resume'), true );
        $this->addActionButton('submit-details', 'Afficher (détails)', $url('service/default', ['action' => 'index']));
        $this->addActionButton('submit-export' , 'Exporter (CSV)'    , $url('service/export'));
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
            'type-intervenant' => array(
                'required' => false
            ),
            'structure-aff' => array(
                'required' => false
            ),
            'intervenant' => array(
                'required' => false
            ),
            'element-pedagogique' => array(
                'required' => false,
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
     * @return \Application\Service\TypeIntervenant
     */
    protected function getServiceTypeIntervenant()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeIntervenant');
    }

    /**
     * @return \Application\Service\TypeVolumeHoraire
     */
    protected function getServiceTypeVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationTypeVolumeHoraire');
    }

    /**
     * @return \Application\Service\EtatVolumeHoraire
     */
    protected function getServiceEtatVolumeHoraire()
    {
        return $this->getServiceLocator()->getServiceLocator()->get('applicationEtatVolumeHoraire');
    }

}