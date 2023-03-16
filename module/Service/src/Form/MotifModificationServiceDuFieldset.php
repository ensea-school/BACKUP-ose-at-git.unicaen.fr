<?php

namespace Service\Form;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractFieldset;
use Doctrine\Laminas\Hydrator\DoctrineObject;
use Laminas\Validator\LessThan;
use Service\Entity\Db\ModificationServiceDu;
use Service\Entity\Db\MotifModificationServiceDu;
use Service\Service\MotifModificationServiceDuServiceAwareTrait;

/**
 * Description of MotifModificationServiceDu
 *
 */
class MotifModificationServiceDuFieldset extends AbstractFieldset
{
    use MotifModificationServiceDuServiceAwareTrait;

    /**
     * This function is automatically called when creating element with factory. It
     * allows to perform various operations (add elements...)
     */
    public function init()
    {
        $serviceMMSD = $this->getServiceMotifModificationServiceDu();

        $this->setHydrator(new MotifModificationServiceDuHydrator($serviceMMSD->getEntityManager()))
            ->setObject(new ModificationServiceDu());

        $motifs = $serviceMMSD->getList($serviceMMSD->finderByHistorique());

        $this->add([
            'type'       => 'Select',
            'name'       => 'motif',
            'options'    => [
                'label'         => 'Motif',
                'value_options' => \UnicaenApp\Util::collectionAsOptions($motifs),
                'empty_option'  => "(Sélectionnez un motif...)",
            ],
            'attributes' => [
                'title' => "Motif",
                'class' => 'modification-service-du modification-service-du-motif',
            ],
        ]);

        $this->add([
            'type'       => 'Text',
            'name'       => 'heures',
            'options'    => [
                'label' => "Nombre d'heures",
            ],
            'attributes' => [
                'value' => 0,
                'title' => "Nombre d'heures",
                'class' => 'modification-service-du modification-service-du-heures',
            ],
        ]);

        $this->add([
            'type'       => 'Textarea',
            'name'       => 'commentaires',
            'options'    => [
                'label' => "Commentaires",
            ],
            'attributes' => [
                'title' => "Commentaires éventuels",
                'class' => 'modification-service-du modification-service-du-commentaires',
            ],
        ]);

        $this->add([
            'type'       => 'Button',
            'name'       => 'remove',
            'options'    => [
                'label'         => "<i class=\"fas fa-minus\"></i> Supprimer",
                'label_options' => ['disable_html_escape' => true],
            ],
            'attributes' => [
                'title' => "Supprimer cette ligne",
                'class' => 'modification-service-du modification-service-du-supprimer btn btn-secondary btn-sm',
            ],
        ]);

        return $this;
    }



    /**
     *
     * @return \Laminas\Validator\LessThan|null
     */
    protected function getValidatorHeures()
    {
        // recherche de la MotifReferentiel sélectionnée pour plafonner le nombre d'heures
        $v     = null;
        $id    = $this->get('motif')->getValue();
        $p     = function ($item) use ($id) {
            return $id == $item->getId();
        };
        $motif = current(array_filter($this->getMotifs(), $p));
        /* @var $motif MotifModificationServiceDu */
        if ($motif) {
            $v = new LessThan(['max' => $max = (float)$motif->getPlafond(), 'inclusive' => true]);
            $v->setMessages([LessThan::NOT_LESS_INCLUSIVE => "Le plafond pour ce motif est de $max"]);
        }

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
            'motif'  => [
                'required' => true,
            ],
            'heures' => [
                'required'   => true,
                'filters'    => [
                    ['name' => FloatFromString::class],
                ],
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\GreaterThan',
                        'options' => [
                            'min'       => -1,
                            'inclusive' => false,
                            'messages'  => [
                                \Laminas\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être strictement supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
        ];

        return $specs;
    }
}





class MotifModificationServiceDuHydrator extends DoctrineObject
{
    /**
     * Extract values from an object
     *
     * @param ModificationServiceDu $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $array = parent::extract($object);

        $array['heures'] = StringFromFloat::run($object->getHeures());
        $array['motif']  = $object->getMotif() ? $object->getMotif()->getId() : null;

        return $array;
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                 $data
     * @param ModificationServiceDu $object
     *
     * @return object
     */
    public function hydrate(array $data, object $object): object
    {
        parent::hydrate($data, $object);

        $object->setHeures($data['heures']);

        return $object;
    }
}