<?php

namespace Application\Form\Service;

use Application\Entity\Service\Recherche;
use Application\Form\AbstractForm;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeIntervenantAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use Zend\Form\Element\Select;
use Zend\Form\Element\Hidden;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\ElementInterface;

/**
 * Description of RechercheForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheForm extends AbstractForm
{
    use StructureAwareTrait;
    use TypeIntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;


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
        $hydrator = $this->getServiceLocator()->getServiceLocator()->get('ServiceRechercheFormHydrator');
        /* @var $hydrator RechercheHydrator */
        $this->setHydrator( $hydrator )
             ->setAllowedObjectBindingClass(Recherche::class);

        $this   ->setAttribute('method', 'get')
                ->setAttribute('class', 'service-recherche')
                ->setAttribute('id', $this->getId());

        $typeIntervenant = new \Zend\Form\Element\Radio('type-intervenant');
        $typeIntervenant
                ->setValueOptions([
                    '' => "Peu importe",
                    $this->getServiceTypeIntervenant()->getPermanent()->getId() => "Permanent",
                    $this->getServiceTypeIntervenant()->getExterieur()->getId() => "Vacataire"])
                ->setValue('')
                ->setAttribute('data-intervenant-exterieur-id', $this->getServiceTypeIntervenant()->getExterieur()->getId())
                ->setLabel("Statut :");
        $this->add($typeIntervenant);

        $structures = $this->getServiceStructure()->getList(
                          $this->getServiceStructure()->finderByEnseignement(
                              $this->getServiceStructure()->finderByNiveau(2)
                          )
                      );
        $this->add([
            'name'       => 'structure-aff',
            'options'    => [
                'label' => "Structure d'affectation:",
                'empty_option' => "(Toutes)",
                'disable_inarray_validator' => true,
                'label_attributes' => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
            ],
            'attributes' => [
                'title' => "Structure gestionnaire de l'enseignement",
                'class' => 'input-sm',
            ],
            'type' => 'Select',
        ]);
        $this->get('structure-aff')->setValueOptions( \UnicaenApp\Util::collectionAsOptions( $structures ) );

        $intervenant = new SearchAndSelect('intervenant');
        $intervenant
                ->setAutocompleteSource($this->getUrl(
                    'recherche',
                    ['action' => 'intervenantFind'],
                    ['query' => ['having-services' => 1]]
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
        $etatVolumeHoraire->setValue( $this->getServiceEtatVolumeHoraire()->getSaisi()->getId() );
        $this->add($etatVolumeHoraire);


        $action = new Hidden('action');
        $action->setValue('afficher');
        $this->add($action);


        $this->addActionButton('submit-resume' , 'Afficher (résumé)' , $this->getUrl('service/resume'), true );
        $this->addActionButton('submit-details', 'Afficher (détails)', $this->getUrl('service/default', ['action' => 'index']));
        $this->addActionButton('submit-export' , 'Exporter (CSV)'    , $this->getUrl('service/export'));
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'type-intervenant' => [
                'required' => false
            ],
            'structure-aff' => [
                'required' => false
            ],
            'intervenant' => [
                'required' => false
            ],
            'element-pedagogique' => [
                'required' => false,
            ],
        ];
    }
}