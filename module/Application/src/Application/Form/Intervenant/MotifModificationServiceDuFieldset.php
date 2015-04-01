<?php

namespace Application\Form\Intervenant;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use Zend\Form\Fieldset;
use Zend\Validator\LessThan;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\InputFilter\InputFilterProviderInterface;;
use Doctrine\Common\Collections\Collection;
use Application\Entity\Db\ModificationServiceDu;
use Application\Entity\Db\MotifModificationService;

/**
 * Description of MotifModificationServiceDu
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MotifModificationServiceDuFieldset extends Fieldset implements EntityManagerAwareInterface, InputFilterProviderInterface//, ContextProviderAwareInterface
{
    use EntityManagerAwareTrait;
//    use ContextProviderAwareTrait;

    /**
     * @var Collection
     */
    protected $motifsPossibles;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setHydrator(new MotifModificationServiceDuHydrator($this->getMotifs()))
             ->setObject(new ModificationServiceDu());

//        $this->setLabel("Motif");

        $this->add([
            'name'       => 'motif',
            'options'    => [
                'label' => "Motif",
            ],
            'attributes' => [
                'title' => "Motif",
                'class' => 'modification-service-du modification-service-du-motif',
            ],
            'type'       => 'Select',
        ]);

        $this->add([
            'name'       => 'heures',
            'options'    => [
                'label' => "Nombre d'heures",
            ],
            'attributes' => [
                'value' => "0",
                'title' => "Nombre d'heures",
                'class' => 'modification-service-du modification-service-du-heures'
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'commentaires',
            'options'    => [
                'label' => "Commentaires",
            ],
            'attributes' => [
                'title' => "Commentaires éventuels",
                'class' => 'modification-service-du modification-service-du-commentaires'
            ],
            'type'       => 'Textarea',
        ]);

        $this->add([
            'name'       => 'remove',
            'options'    => [
                'label' => "<span class=\"glyphicon glyphicon-minus\"></span> Supprimer",
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'title' => "Supprimer cette ligne",
                'class' => 'modification-service-du modification-service-du-supprimer btn btn-default btn-xs'
            ],
            'type'       => 'Button',
        ]);

        // liste déroulante des motifs
        $options = [];
        $options[''] = "(Sélectionnez un motif...)"; // setEmptyOption() pas utilisé car '' n'est pas compris dans le validateur InArray
        foreach ($this->getMotifs() as $item) {
            $options[$item->getId()] = "" . $item;
        }
        $this->get('motif')->setValueOptions($options);//->setEmptyOption("(Sélectionnez une motif...)");

        return $this;
    }

    protected function getMotifs()
    {
        if (null === $this->motifsPossibles) {
            $repoMotif = $this->getEntityManager()->getRepository('Application\Entity\Db\MotifModificationServiceDu'); /* @var $repoMotif \Doctrine\ORM\EntityRepository */
            $this->motifsPossibles = $repoMotif->findBy([], ['libelle' => 'asc']);
            if (!$this->motifsPossibles) {
                throw new \Common\Exception\RuntimeException("Aucun motif de modification de service dû trouvé dans la base.");
            }
        }
        return $this->motifsPossibles;
    }

    /**
     *
     * @return \Zend\Validator\LessThan|null
     */
    protected function getValidatorHeures()
    {
        // recherche de la MotifReferentiel sélectionnée pour plafonner le nombre d'heures
        $v  = null;
        $id = $this->get('motif')->getValue();
        $p  = function($item) use ($id) {
            return $id == $item->getId();
        };
        $motif = current(array_filter($this->getMotifs(), $p)); /* @var $motif MotifModificationService */
        if ($motif) {
            $v = new LessThan(['max' => $max = (float)$motif->getPlafond(), 'inclusive' => true]);
            $v->setMessages([LessThan::NOT_LESS_INCLUSIVE => "Le plafond pour ce motif est de $max"]);
        }

        return $v;
    }

    /**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $specs = [
            'motif' => [
                'required'   => true,
                'validators' => [
                    [
                        'name' => 'Zend\Validator\NotEmpty',
                        'options' => [
                            'messages' => [
                                \Zend\Validator\NotEmpty::IS_EMPTY => "Le motif est requis",
                            ],
                        ],
                    ],
                ],
            ],
            'heures'   => [
                'required' => true,
                'filters'    => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    new \Zend\Filter\PregReplace(['pattern' => '/,/', 'replacement' => '.']),
                ],
                'validators' => [
//                    array(
//                        'name' => 'Zend\Validator\NotEmpty',
//                        'options' => array(
//                            'string',
//                            'integer',
//                            'zero',
//                            'messages' => array(
//                                \Zend\Validator\NotEmpty::IS_EMPTY => "Un nombre d'heures non nul est requis",
//                            ),
//                        ),
//                    ),
                    [
                        'name' => 'Zend\Validator\GreaterThan',
                        'options' => [
                            'min' => 0,
                            'inclusive' => false,
                            'messages' => [
                                \Zend\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être strictement supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
        ];

//        if (($validator = $this->getValidatorHeures())) {
//            $specs['heures']['validators'][] = $validator;
//        }

        return $specs;
    }
}

class MotifModificationServiceDuHydrator implements HydratorInterface
{
    /**
     * @var MotifModificationService[]
     */
    protected $motifsPossibles;

    /**
     *
     * @param MotifModificationService[] $motifsPossibles
     */
    public function __construct($motifsPossibles)
    {
        $this->motifsPossibles = [];
        foreach ($motifsPossibles as $v) {
            $this->motifsPossibles[$v->getId()] = $v;
        }
    }

    /**
     * Extract values from an object
     *
     * @param  ModificationServiceDu $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'motif'        => $object->getMotif()->getId(),
            'heures'       => floatval($object->getHeures()),
            'commentaires' => $object->getCommentaires(),
        ];
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ModificationServiceDu $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object
                ->setMotif($this->motifsPossibles[intval($data['motif'])])
                ->setHeures(floatval($data['heures']))
                ->setCommentaires($data['commentaires'] ?: null);

        return $object;
    }
}