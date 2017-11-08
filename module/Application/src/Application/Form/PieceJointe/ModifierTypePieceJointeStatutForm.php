<?php

namespace Application\Form\PieceJointe;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypePieceJointeStatutAwareTrait;
use Zend\Form\Element\Csrf;
use Application\Service\Traits\AnneeAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Util;
use Zend\Form\ElementInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Select;
use Zend\Form\Element\Hidden;
use UnicaenApp\Form\Element\SearchAndSelect;
Use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\Etape;
use Application\Entity\Db\EtatVolumeHoraire;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\TypeIntervenant;
use Application\Entity\Db\TypeVolumeHoraire;
use Application\Entity\NiveauEtape;
use Application\Entity\Service\Recherche;
use Application\Form\OffreFormation\Traits\ElementPedagogiqueRechercheFieldsetAwareTrait;
use Application\Provider\Privilege\Privileges;
use Application\Service\Traits\EtatVolumeHoraireAwareTrait;
use Application\Service\Traits\IntervenantAwareTrait;
use Application\Service\Traits\NiveauEtapeAwareTrait;
use Application\Service\Traits\StructureAwareTrait;
use Application\Service\Traits\TypeIntervenantAwareTrait;
use Application\Service\Traits\TypeVolumeHoraireAwareTrait;
use UnicaenAuth\Service\Traits\AuthorizeServiceAwareTrait;


/**
 * Description of ModifierTypePieceJointeSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class ModifierTypePieceJointeStatutForm extends AbstractForm implements EntityManagerAwareInterface
{
    use AnneeAwareTrait;
    use EntityManagerAwareTrait;
    use StructureAwareTrait;
    use TypeIntervenantAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use EtatVolumeHoraireAwareTrait;
    use AuthorizeServiceAwareTrait;
    use IntervenantAwareTrait;
    use NiveauEtapeAwareTrait;
    use ElementPedagogiqueRechercheFieldsetAwareTrait;

    /**
     * Liste des boutons d'actions
     *
     * @var ElementInterface
     */
    protected $actionButtons = [];

    /**
     *
     * @var string
     */
    private $id;


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
        if (!isset($attributes['class'])) $attributes['class'] = 'btn ' . ($primary ? 'btn-primary' : 'btn-default');
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
     * @return \Zend\Form\ElementInterface[]
     */
    public function getActionButtons()
    {
        return $this->actionButtons;
    }

    public function init()
    {
        $hydrator = new TypePieceJointeStatutHydrator();
        $this->setHydrator($hydrator);
        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'premier-recrutement',
            'options' => [
                'label' => 'Uniquement an cas de premier recrutement',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'type'       => 'Checkbox',
            'name'       => 'typePieceJointe',
            'options'    => [
                'label'         => "La pièce justifitative doit être fournie obligatoirement",
            ],
        ]);

        $this->add([
            'name' => 'seuil-hetd',
            'options' => [
                'label' => "Nombre d'heures min.",
            ],
            'type' => 'Number',
            'attributes' => [
                'min' => '0',
            ],
        ]);

        $this->add([
            'name' => 'changement-rib',
            'options' => [
                'label' => 'Uniquement en cas de changement de RIB',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'fc',
            'options' => [
                'label' => 'Limité aux actions de formation continue',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-debut',
            'options'    => [
                'empty_option'  => 'Pas de limite',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'À partir de',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-fin',
            'options'    => [
                'empty_option'  => 'Pas de limite',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'Jusqu\'à',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary'
            ],
        ]);

            return $this;
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
            'typePieceJointe' => [
                'required' => true,
            ],
            'seuil-hetd' => [
                'required' => false,
                'validators' => [
                    [
                        'name' => 'Zend\Validator\GreaterThan',
                        'options' => [
                            'min' => 0,
                            'inclusive' => true,
                            'messages' => [
                                \Zend\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
            'changement-rib' => [
                'required' => true,
            ],
            'premier-recrutement' => [
                'required' => true,
            ],
            'fc' => [
                'required' => true,
            ],
            'annee-debut' => [
                'required' => false,
            ],
            'annee-fin'   => [
                'required' => false,
            ],
        ];
    }

}

class TypePieceJointeStatutHydrator implements HydratorInterface
{
    use TypePieceJointeStatutAwareTrait;
    use AnneeAwareTrait;
    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
{
    $object->setPremierRecrutement($data['premier-recrutement']);
    $object->setChangementRIB($data['changement-rib']);
    $object->setObligatoire($data['typePieceJointe']);
    $object->setSeuilHetd($data['seuil-hetd']);
    if (array_key_exists('annee-debut', $data)) {
        $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
    }
    if (array_key_exists('annee-fin', $data)) {
        $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
    }
    $object->setFC($data['fc']);
    return $object;
}


    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return array
     */
    public function extract($object)
{
    $data = [
        'id' => $object->getId(),
        'typePieceJointe' => $object->getObligatoire(),
        'premier-recrutement' => $object->getPremierRecrutement(),
        'seuil-hetd' => $object->getSeuilHeures(),
        'premier-recrutement' => $object->getPremierRecrutement(),
        'changement-rib' => $object->getChangementRIB(),
        'fc' => $object->getFC(),
        'annee-debut'       => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
        'annee-fin'         => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
    ];

    return $data;
}
}