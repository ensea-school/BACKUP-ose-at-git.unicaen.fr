<?php

namespace Application\Form\ServiceReferentiel;

use Application\Entity\Db\FonctionReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\ServiceReferentiel;
use Application\Entity\Db\Structure;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\FonctionReferentielServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Application\Service\Traits\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;
use Laminas\Filter\PregReplace;
use Laminas\Validator\Callback;
use Laminas\Validator\NotEmpty;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of SaisieFieldset
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldset extends AbstractFieldset
{
    use ContextServiceAwareTrait;
    use LocalContextServiceAwareTrait;
    use StructureServiceAwareTrait;
    use FonctionReferentielServiceAwareTrait;

    /**
     * @var Structure[]
     */
    protected $structures;



    public function __construct($name = null, $options = [])
    {
        parent::__construct('service', $options);
    }



    public function init()
    {

        $hydrator = new SaisieFieldsetHydrator();
        $hydrator->setEntityManager($this->getEntityManager());

        $this->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(ServiceReferentiel::class);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $role = $this->getServiceContext()->getSelectedIdentityRole();

        if (!($role && $role->getIntervenant())) {
            $intervenant = new SearchAndSelect('intervenant');
            $intervenant->setRequired(true)
                ->setSelectionRequired(true)
                ->setAutocompleteSource(
                    $this->getUrl('recherche', ['action' => 'intervenantFind'])
                )
                ->setLabel("Intervenant :")
                ->setAttributes(['title' => "Saisissez le nom suivi éventuellement du prénom (2 lettres au moins)"]);
            $this->add($intervenant);
        }

        $this->add([
            'name'       => 'structure',
            'options'    => [
                'label' => "Composante ou Service :",
            ],
            'attributes' => [
                'title'            => "Structure / Service concernée",
                'class'            => 'fonction-referentiel fonction-referentiel-structure input-sm selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'formation',
            'options'    => [
                'label' => "Formation correspondante :",
            ],
            'attributes' => [
                'title' => "Formation correspondante",
                'class' => 'fonction-referentiel fonction-referentiel-formation input-sm',
            ],
            'type'       => 'Textarea',
        ]);

        $this->add([
            'name'       => 'fonction',
            'options'    => [
                'label' => "Fonction :",
            ],
            'attributes' => [
                'title'            => "Fonction référentielle",
                'class'            => 'fonction-referentiel fonction-referentiel-fonction input-sm selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'heures',
            'options'    => [
                'label' => "Heures :",
            ],
            'attributes' => [
                'title' => "Nombre d'heures",
                'class' => 'fonction-referentiel fonction-referentiel-heures input-sm',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'commentaires',
            'options'    => [
                'label' => "Commentaires :",
            ],
            'attributes' => [
                'title' => "Commentaires éventuels",
                'class' => 'fonction-referentiel fonction-referentiel-commentaires input-sm',
            ],
            'type'       => 'Textarea',
        ]);

        $this->get('structure')->setValueOptions(Util::collectionAsOptions($this->getStructures()));//->setEmptyOption("(Sélectionnez une structure...)");
        $this->get('fonction')->setValueOptions($this->getFonctions());//->setEmptyOption("(Sélectionnez une fonction...)");
    }



    protected function getStructures()
    {
        if (!$this->structures) {
            $qb = $this->getServiceStructure()->finderByEnseignement();
            if ($univ = $this->getServiceStructure()->getRacine()) {
                $this->structures = [$univ->getId() => $univ] + $this->getServiceStructure()->getList($qb);
            } else {
                $this->structures = $this->getServiceStructure()->getList($qb);
            }
        }

        return $this->structures;
    }



    public function getFonctions()
    {
        $fncs      = $this->getServiceFonctionReferentiel()->getList($this->getServiceFonctionReferentiel()->finderByHistorique());
        $fonctions = [];
        foreach ($fncs as $id => $fonction) {
            if ($fonction->getFille()->count() > 0) {

                $filles = [];
                foreach ($fonction->getFille() as $fille) {
                    $filles[$fille->getId()] = (string)$fille;
                    asort($filles);
                }
                $fonctions[$fonction->getId()] = ['label' => (string)$fonction, 'options' => $filles];
            } elseif (!$fonction->getParent()) {
                $fonctions[$id] = (string)$fonction;
            }
        }
        asort($fonctions);
        return $fonctions;
    }



    public function initFromContext()
    {
        /* Peuple le formulaire avec les valeurs issues du contexte local */
        $cl = $this->getServiceLocalContext();
        if ($this->has('intervenant') && $cl->getIntervenant()) {
            $this->get('intervenant')->setValue([
                'id'    => $cl->getIntervenant()->getId(),
                'label' => (string)$cl->getIntervenant(),
            ]);
        }

        if ($structure = $this->getServiceContext()->getSelectedIdentityRole()->getStructure() ?: $cl->getStructure()) {
            $this->get('structure')->setValue($structure->getId());
        }
    }



    public function saveToContext()
    {
        $cl = $this->getServiceLocalContext();

        if (($structureId = $this->get('structure')->getValue())) {
            $cl->setStructure($this->getServiceStructure()->get($structureId));
        } else {
            $cl->setStructure(null);
        }
    }



    /**
     *
     * @return Callback|null
     */
    protected function getValidatorStructure()
    {
        // recherche de la FonctionReferentiel sélectionnée pour connaître la structure associée éventuelle
        $value          = $this->get('fonction')->getValue();
        $fonctionSaisie = $this->getServiceFonctionReferentiel()->get($value);
        if (!$fonctionSaisie) {
            return null;
        }

        // recherche de la Structure sélectionnée
        $structures      = $this->getStructures();
        $value           = $this->get('structure')->getValue();
        $structureSaisie = isset($structures[$value]) ? $structures[$value] : null;
        if (!$structureSaisie) {
            return null;
        }

        // structure éventuelle associée à la fonction
        $structureFonction = $fonctionSaisie->getStructure();

        // si aucune structure n'est associée à la fonction, on vérifie simplement que la structure sélectionnée est de niveau 2
        if (!$structureFonction) {
            $callback = function () use ($structureSaisie) {
                return true;
            };
            $message  = "Composante d'enseignement requise";
        } // si une structure est associée à la fonction, la structure sélectionnée soit être celle-là
        else {
            $callback = function () use ($structureSaisie, $structureFonction) {
                return $structureSaisie === $structureFonction;
            };
            $message  = sprintf("Structure obligatoire : '%s'", $structureFonction);
        }

        $v = new Callback($callback);
        $v->setMessages([Callback::INVALID_VALUE => $message]);

        return $v;
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $specs = [
            'structure'    => [
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "La structure est requise",
                            ],
                        ],
                    ],
                ],
            ],
            'fonction'     => [
                'required'   => true,
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\NotEmpty',
                        'options' => [
                            'messages' => [
                                NotEmpty::IS_EMPTY => "La fonction référentielle est requise",
                            ],
                        ],
                    ],
                ],
            ],
            'heures'       => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Laminas\Filter\StringTrim'],
                    new PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'commentaires' => [
                'required' => false,
                'filters'  => [
                    ['name' => 'Laminas\Filter\StringTrim'],
                ],
            ],
            'formation'    => [
                'required' => false,
                'filters'  => [
                    ['name' => 'Laminas\Filter\StringTrim'],
                ],
            ],
        ];

        if (($validator = $this->getValidatorStructure())) {
            $specs['structure']['validators'][] = $validator;
        }

        return $specs;
    }

}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array              $data
     * @param ServiceReferentiel $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $em = $this->getEntityManager();

        $intervenant = isset($data['intervenant']['id']) ? (int)$data['intervenant']['id'] : null;
        $object->setIntervenant($intervenant ? $em->getRepository(Intervenant::class)->findOneBySourceCode($intervenant) : null);

        $structure = isset($data['structure']) ? (int)$data['structure'] : null;
        $object->setStructure($structure ? $em->find(Structure::class, $structure) : null);

        $fonction = isset($data['fonction']) ? (int)$data['fonction'] : null;
        $object->setFonction($fonction ? $em->find(FonctionReferentiel::class, $fonction) : null);

        $heures = isset($data['heures']) ? FloatFromString::run($data['heures']) : 0;
        $object->getVolumeHoraireReferentielListe()->setHeures($heures);

        $commentaires = isset($data['commentaires']) ? $data['commentaires'] : null;
        $object->setCommentaires($commentaires);

        $formation = isset($data['formation']) ? $data['formation'] : null;
        $object->setFormation($formation);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param ServiceReferentiel $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [];

        if ($object) {
            $data['id'] = $object->getId();
        }

        if ($object->getIntervenant()) {
            $data['intervenant'] = [
                'id'    => $object->getIntervenant()->getId(),
                'label' => (string)$object->getIntervenant(),
            ];
        } else {
            $data['intervenant'] = null;
        }

        if ($object->getStructure()) {
            $data['structure'] = $object->getStructure()->getId();
        } else {
            $data['structure'] = null;
        }

        if ($object->getFonction()) {
            $data['fonction'] = $object->getFonction()->getId();
        } else {
            $data['fonction'] = null;
        }

        $data['heures'] = StringFromFloat::run($object->getVolumeHoraireReferentielListe()->getHeures());

        $data['commentaires'] = $object->getCommentaires();
        $data['formation']    = $object->getFormation();

        return $data;
    }
}