<?php

namespace Service\Form;

use Application\Form\AbstractForm;
use Application\Provider\Privileges;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Intervenant\Service\TypeIntervenantServiceAwareTrait;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\ElementInterface;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Entity\NiveauEtape;
use OffreFormation\Form\Traits\ElementPedagogiqueRechercheFieldsetAwareTrait;
use OffreFormation\Service\Traits\NiveauEtapeServiceAwareTrait;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use Service\Service\EtatVolumeHoraireServiceAwareTrait;
use Service\Service\TypeVolumeHoraireServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenAuthentification\Service\Traits\AuthorizeServiceAwareTrait;


/**
 * Description of RechercheForm
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheForm extends AbstractForm
{
    use StructureServiceAwareTrait;
    use TypeIntervenantServiceAwareTrait;
    use TypeVolumeHoraireServiceAwareTrait;
    use EtatVolumeHoraireServiceAwareTrait;
    use AuthorizeServiceAwareTrait;
    use IntervenantServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;
    use ElementPedagogiqueRechercheFieldsetAwareTrait;

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
     * @param string  $name
     * @param string  $label
     * @param string  $actionUrl
     * @param boolean $primary
     * @param array   $attributes
     *
     * @return self
     */
    public function addActionButton($name, $label, $actionUrl, $primary = false, array $attributes = [])
    {
        if (!isset($attributes['type'])) $attributes['type'] = 'submit';
        if (!isset($attributes['class'])) $attributes['class'] = 'btn ' . ($primary ? 'btn-primary' : 'btn-secondary');
        if (!isset($attributes['onclick'])) $attributes['onclick'] = '$("#' . $this->getId() . '").attr("action", "' . $actionUrl . '");';

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
     * @return \Laminas\Form\ElementInterface[]
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
        $hydrator = new RechercheFormHydrator();
        $hydrator->setEntityManager($this->getEntityManager());
        $hydrator->setServiceIntervenant($this->getServiceIntervenant());
        $hydrator->setServiceNiveauEtape($this->getServiceNiveauEtape());

        $this->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(Recherche::class);

        $this->setAttribute('method', 'get')
            ->setAttribute('class', 'service-recherche')
            ->setAttribute('id', $this->getId());

        $typeIntervenant = new \Laminas\Form\Element\Radio('type-intervenant');
        $typeIntervenant
            ->setValueOptions([
                ''                                                          => "Peu importe",
                $this->getServiceTypeIntervenant()->getPermanent()->getId() => "Permanent",
                $this->getServiceTypeIntervenant()->getExterieur()->getId() => "Vacataire"])
            ->setValue('')
            ->setAttribute('data-intervenant-exterieur-id', $this->getServiceTypeIntervenant()->getExterieur()->getId())
            ->setLabel("Statut :");
        $this->add($typeIntervenant);

        $this->add([
            'name'       => 'structure-aff',
            'type'       => \Lieu\Form\Element\Structure::class,
            'options'    => [
                'label'                     => "Structure d'affectation:",
                'empty_option'              => "(Toutes)",
                'context_filter' => false,
                'disable_inarray_validator' => true,
                'label_attributes'          => [
                    'title' => "Structure gestionnaire de l'enseignement",
                ],
            ],
            'attributes' => [
                'title' => "Structure gestionnaire de l'enseignement",
                'class' => 'input-sm selectpicker',
            ],
        ]);

        $intervenant = new SearchAndSelect('intervenant');
        $intervenant
            ->setAutocompleteSource($this->getUrl(
                'intervenant/recherche',
                [],
                ['query' => ['having-services' => 1]]
            ))
            ->setLabel('Intervenant :');
        $this->add($intervenant);


        $elementPedagogique = $this->getFieldsetOffreFormationElementPedagogiqueRecherche();
        $elementPedagogique->setElementId('element-recherche');
        $elementPedagogique->setName('element-pedagogique');
        $elementPedagogique->setLabel('Enseignement :');
        $elementPedagogique->setNiveauEnabled(false);
        $this->add($elementPedagogique);


        $typeVolumeHoraire = new Select('type-volume-horaire');
        $typeVolumeHoraire->setLabel('Type :');
        $typeVolumeHoraire->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeVolumeHoraire()->getList()));
        $typeVolumeHoraire->setValue($this->getServiceTypeVolumeHoraire()->getPrevu()->getId());
        $this->add($typeVolumeHoraire);


        $etatVolumeHoraire = new Select('etat-volume-horaire');
        $etatVolumeHoraire->setLabel('État :');
        $etatVolumeHoraire->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceEtatVolumeHoraire()->getList()));
        $etatVolumeHoraire->setValue($this->getServiceEtatVolumeHoraire()->getSaisi()->getId());
        $this->add($etatVolumeHoraire);


        $action = new Hidden('action');
        $action->setValue('afficher');
        $this->add($action);

        $this->addActionButton('submit-resume', 'Afficher (résumé)', $this->getUrl('service/resume'), true);
        $this->addActionButton('submit-details', 'Afficher (détails)', $this->getUrl('service'));
        if ($this->getServiceAuthorize()->isAllowed(Privileges::getResourceId(Privileges::ENSEIGNEMENT_EXPORT_CSV))) {
            $this->addActionButton('submit-export-csv', 'Exporter (CSV)', $this->getUrl('service/export-csv'));
        }
        if ($this->getServiceAuthorize()->isAllowed(Privileges::getResourceId(Privileges::ENSEIGNEMENT_EXPORT_PDF))) {
            $this->addActionButton('submit-export-pdf', 'Exporter (PDF)', $this->getUrl('service/export-pdf'));
        }
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'type-intervenant'    => [
                'required' => false,
            ],
            'structure-aff'       => [
                'required' => false,
            ],
            'intervenant'         => [
                'required' => false,
            ],
            'element-pedagogique' => [
                'required' => false,
            ],
        ];
    }
}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheFormHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;
    use IntervenantServiceAwareTrait;
    use NiveauEtapeServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array  $data
     * @param object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* @var $object Recherche */

        $id = isset($data['type-intervenant']) ? (int)$data['type-intervenant'] : null;
        $object->setTypeIntervenant($this->getEntity(TypeIntervenant::class, $id));

        $id = isset($data['structure-aff']) ? (int)$data['structure-aff'] : null;
        $object->setStructureAff($this->getEntity(Structure::class, $id));

        $id = isset($data['intervenant']['id']) ? $data['intervenant']['id'] : null;
        $object->setIntervenant($this->getEntity(Intervenant::class, $id));

        $id = isset($data['element-pedagogique']['structure']) ? (int)$data['element-pedagogique']['structure'] : null;
        $object->setStructureEns($this->getEntity(Structure::class, $id));

        $id = isset($data['element-pedagogique']['niveau']) ? $data['element-pedagogique']['niveau'] : null;
        $object->setNiveauEtape($this->getEntity(NiveauEtape::class, $id));

        $id = isset($data['element-pedagogique']['etape']) ? (int)$data['element-pedagogique']['etape'] : null;
        $object->setEtape($this->getEntity(Etape::class, $id));

        $id = isset($data['element-pedagogique']['element']['id']) ? (int)$data['element-pedagogique']['element']['id'] : null;
        $object->setElementPedagogique($this->getEntity(ElementPedagogique::class, $id));

        $id = isset($data['type-volume-horaire']) ? (int)$data['type-volume-horaire'] : null;
        $object->setTypeVolumeHoraire($this->getEntity(TypeVolumeHoraire::class, $id));

        $id = isset($data['etat-volume-horaire']) ? (int)$data['etat-volume-horaire'] : null;
        $object->setEtatVolumeHoraire($this->getEntity(EtatVolumeHoraire::class, $id));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Service\Entity\Recherche $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'type-intervenant'    => $object->getTypeIntervenant() ? $object->getTypeIntervenant()->getId() : null,
            'structure-aff'       => $object->getStructureAff() ? $object->getStructureAff()->getId() : null,
            'intervenant'         => [
                'id'    => $object->getIntervenant() ? $object->getIntervenant()->getId() : null,
                'label' => $object->getIntervenant() ? (string)$object->getIntervenant() : null,
            ],
            'element-pedagogique' => [
                'structure' => $object->getStructureEns() ? $object->getStructureEns()->getId() : null,
                'niveau'    => $object->getNiveauEtape() ? $object->getNiveauEtape()->getId() : null,
                'etape'     => $object->getEtape() ? $object->getEtape()->getId() : null,
                'element'   => $object->getElementPedagogique(),
            ],
            'type-volume-horaire' => $object->getTypeVolumeHoraire() ? $object->getTypeVolumeHoraire()->getId() : null,
            'etat-volume-horaire' => $object->getEtatVolumeHoraire() ? $object->getEtatVolumeHoraire()->getId() : null,
        ];

        return $data;
    }



    private function getEntity($classname, $id)
    {
        if (!$id) return null;

        switch ($classname) {
            case Intervenant::class:
                return $this->getServiceIntervenant()->getByRouteParam($id);

            case NiveauEtape::class:
                return $this->getServiceNiveauEtape()->get();

            default:
                return $this->getEntityManager()->find($classname, $id);
        }
    }
}