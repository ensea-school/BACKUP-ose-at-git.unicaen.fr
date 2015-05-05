<?php

namespace Application\Form\Intervenant;

use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Fieldset;
use Zend\Validator\LessThan;
use Zend\InputFilter\InputFilterProviderInterface;;
use Application\Entity\Db\ModificationServiceDu;
use Application\Entity\Db\MotifModificationService;

/**
 * Description of MotifModificationServiceDu
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class MotifModificationServiceDuFieldset extends Fieldset implements EntityManagerAwareInterface, InputFilterProviderInterface
{
    use EntityManagerAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $this->setHydrator(new MotifModificationServiceDuHydrator($this->getEntityManager()))
             ->setObject(new ModificationServiceDu());

        $motifSelect = new \DoctrineORMModule\Form\Element\EntitySelect('motif', [
            'label' => 'Motif',
            'empty_option' => "(Sélectionnez un motif...)"
        ]);
        $motifSelect->setAttributes([
            'title' => "Motif",
            'class' => 'modification-service-du modification-service-du-motif',
        ]);
        $motifSelect->getProxy()
                ->setFindMethod(['name' => 'findBy', 'params' => ['criteria' => [], 'orderBy' => ['libelle' => 'ASC']]])
                ->setObjectManager($this->getEntityManager())
                ->setTargetClass('Application\Entity\Db\MotifModificationServiceDu');
        $this->add($motifSelect);

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

        return $this;
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
                'required' => true,
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

class MotifModificationServiceDuHydrator extends \DoctrineModule\Stdlib\Hydrator\DoctrineObject
{
    /**
     * Extract values from an object
     *
     * @param  ModificationServiceDu $object
     * @return array
     */
    public function extract($object)
    {
        $array = parent::extract($object);
        
        $array['heures'] = floatval($object->getHeures());
        
        return $array;
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
        parent::hydrate($data, $object);
        
        $object->setHeures(floatval($data['heures']));

        return $object;
    }
}