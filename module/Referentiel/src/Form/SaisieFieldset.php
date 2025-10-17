<?php

namespace Referentiel\Form;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractFieldset;
use Application\Provider\Privileges;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\LocalContextServiceAwareTrait;
use Laminas\Filter\PregReplace;
use Laminas\Form\Element\Hidden;
use Laminas\Hydrator\HydratorInterface;
use Laminas\Validator\Callback;
use Laminas\Validator\NotEmpty;
use Lieu\Entity\Db\Structure;
use Lieu\Service\StructureServiceAwareTrait;
use Paiement\Entity\Db\MotifNonPaiement;
use Paiement\Service\MotifNonPaiementServiceAwareTrait;
use Referentiel\Entity\Db\FonctionReferentiel;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Service\FonctionReferentielServiceAwareTrait;
use Service\Entity\Db\Tag;
use Service\Service\TagServiceAwareTrait;
use Unicaen\Framework\Application\Application;
use Unicaen\Framework\Authorize\Authorize;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenApp\Util;


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
    use TagServiceAwareTrait;
    use MotifNonPaiementServiceAwareTrait;
    use ContextServiceAwareTrait;

    /**
     * @var Structure[]
     */
    protected $structures;

    protected Authorize $authorize;



    public function __construct($name = null, $options = [])
    {
        $this->authorize = Application::getInstance()->container()->get(Authorize::class);

        parent::__construct('service', $options);
    }



    public function init()
    {

        $hydrator = new SaisieFieldsetHydrator();
        $hydrator->setEntityManager($this->getEntityManager());

        $this->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(ServiceReferentiel::class);

        $canEditMNP = $this->authorize->isAllowedPrivilege(Privileges::MOTIF_NON_PAIEMENT_EDITION);
        $canEditTag = $this->authorize->isAllowedPrivilege(Privileges::TAG_EDITION);

        $this->add([
            'name' => 'id',
            'type' => 'Hidden',
        ]);

        $this->add([
            'name' => 'idPrev',
            'type' => 'Hidden',
        ]);


        $this->add([
            'name'       => 'structure',
            'type'       => \Lieu\Form\Element\Structure::class,
            'options'    => [
                'label' => "Composante ou Service :",
            ],
            'attributes' => [
                'title' => "Structure / Service concernée",
                'class' => 'fonction-referentiel fonction-referentiel-structure input-sm selectpicker',
            ],
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
        if ($canEditMNP) {
            $this->add([
                'type'       => 'Select',
                'name'       => 'motif-non-paiement',
                'options'    => [
                    'label'         => "Motif de non paiement :",
                    'empty_option'  => "Aucun motif : paiement prévu",
                    'value_options' => Util::collectionAsOptions($this->getMotifsNonPaiement()),
                ],
                'attributes' => [
                    'value' => "",
                    'title' => "Motif de non paiement",
                    'class' => 'volume-horaire volume-horaire-motif-non-paiement input-sm',
                ],
            ]);
        } else {
            $this->add(new Hidden('motif-non-paiement'));

        }


        //Gestion des tags
        if ($canEditTag) {
            $this->add([
                'type'       => 'Select',
                'name'       => 'tag',
                'options'    => [
                    'label'         => "Tag :",
                    'empty_option'  => "Aucun tag",
                    'value_options' => Util::collectionAsOptions($this->getServiceTag()->getList()),
                ],
                'attributes' => [
                    'value' => "",
                    'title' => "Tag",
                    'class' => 'volume-horaire volume-horaire-tag input-sm',
                ],
            ]);
        } else {
            $this->add(new Hidden('tag'));
        }


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
        $fncs = $this->getServiceFonctionReferentiel()->getList($this->getServiceFonctionReferentiel()->finderByHistorique());
        $fonctions = [];
        foreach ($fncs as $id => $fonction) {
            if ($fonction->getFille()->count() > 0) {

                $filles = [];
                foreach ($fonction->getFille() as $fille) {
                    $filles[$fille->getId()] = (string)$fille;
                }
                asort($filles);
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

        if ($structure = $this->getServiceContext()->getStructure() ?: $cl->getStructure()) {
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
     * @return MotifNonPaiement[]
     */
    protected function getMotifsNonPaiement()
    {
        $qb = $this->getServiceMotifNonPaiement()->finderByHistorique();

        return $this->getServiceMotifNonPaiement()->getList($qb);
    }



    /**
     *
     * @return Callback|null
     */
    protected function getValidatorStructure()
    {
        // recherche de la FonctionReferentiel sélectionnée pour connaître la structure associée éventuelle
        $value = $this->get('fonction')->getValue();
        $fonctionSaisie = $this->getServiceFonctionReferentiel()->get($value);
        if (!$fonctionSaisie) {
            return null;
        }

        // recherche de la Structure sélectionnée
        $structures = $this->getStructures();
        $value = $this->get('structure')->getValue();
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
            $message = "Composante d'enseignement requise";
        } // si une structure est associée à la fonction, la structure sélectionnée soit être celle-là
        else {
            $callback = function () use ($structureSaisie, $structureFonction) {
                return $structureSaisie === $structureFonction;
            };
            $message = sprintf("Structure obligatoire : '%s'", $structureFonction);
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
            'motif-non-paiement' => [
                'required' => false,
            ],
            'tag'                => [
                'required' => false,
            ],
            'structure'          => [
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
            'fonction'           => [
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
            'heures'             => [
                'required' => true,
                'filters'  => [
                    ['name' => 'Laminas\Filter\StringTrim'],
                    new PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
            ],
            'commentaires'       => [
                'required' => false,
                'filters'  => [
                    ['name' => 'Laminas\Filter\StringTrim'],
                ],
            ],
            'formation'          => [
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
     * @param array $data
     * @param ServiceReferentiel $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $em = $this->getEntityManager();

//        $intervenant = isset($data['intervenant']['id']) ? (int)$data['intervenant']['id'] : null;
//        $object->setIntervenant($intervenant ? $em->getRepository(Intervenant::class)->findOneBySourceCode($intervenant) : null);

        $structure = isset($data['structure']) ? (int)$data['structure'] : null;
        $object->setStructure($structure ? $em->find(Structure::class, $structure) : null);

        $fonction = isset($data['fonction']) ? (int)$data['fonction'] : null;
        $object->setFonctionReferentiel($fonction ? $em->find(FonctionReferentiel::class, $fonction) : null);

        $heures = isset($data['heures']) ? FloatFromString::run($data['heures']) : 0;
        $object->getVolumeHoraireReferentielListe()->setHeures($heures);

        $tag = isset($data['tag']) ? (int)$data['tag'] : null;
        $object->setTag($tag ? $em->find(Tag::class, $tag) : null);

        $motifNonPaiement = isset($data['motif-non-paiement']) ? (int)$data['motif-non-paiement'] : null;
        $object->setMotifNonPaiement($motifNonPaiement ? $em->find(MotifNonPaiement::class, $motifNonPaiement) : null);

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
            $data['idPrev'] = $object->getId();
        }

//        if ($object->getIntervenant()) {
//            $data['intervenant'] = [
//                'id'    => $object->getIntervenant()->getId(),
//                'label' => (string)$object->getIntervenant(),
//            ];
//        } else {
//            $data['intervenant'] = null;
//        }

        if ($object->getStructure()) {
            $data['structure'] = $object->getStructure()->getId();
        } else {
            $data['structure'] = null;
        }

        if ($object->getFonctionReferentiel()) {
            $data['fonction'] = $object->getFonctionReferentiel()->getId();
        } else {
            $data['fonction'] = null;
        }

        if ($object->getTag()) {
            $data['tag'] = $object->getTag()->getId();
        } else {
            $data['tag'] = null;
        }

        if ($object->getMotifNonPaiement()) {
            $data['motif-non-paiement'] = $object->getMotifNonPaiement()->getId();
        } else {
            $data['motif-non-paiement'] = null;
        }


        $data['heures'] = StringFromFloat::run($object->getVolumeHoraireReferentielListe()->getHeures());

        $data['commentaires'] = $object->getCommentaires();
        $data['formation'] = $object->getFormation();

        return $data;
    }
}