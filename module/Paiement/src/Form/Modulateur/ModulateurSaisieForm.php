<?php

namespace Paiement\Form\Modulateur;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Paiement\Service\TypeModulateurServiceAwareTrait;

/**
 * Description of modulateurSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class ModulateurSaisieForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new ModulateurHydrator();
        //** $hydrator->setServiceDomaineFonctionnel($this->getServiceDomaineFonctionnel());
        /**
         * @var $typesModu \Paiement\Entity\Db\typeModulateur[]
         */
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => "Code",
            ],
            'attributes' => [
                'id' => uniqid('code'),
            ],
            'type'       => 'Text',
        ]);
        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libelle",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
                'name' => 'type-modulateur',
                'type' => 'Hidden',
            ]
        );
        $this->add([
            'name'       => 'ponderation-service-du',
            'options'    => [
                'label' => 'Ponderation service du',
            ],
            'attributes' => [
            ],
            'type'       => 'Text',
        ]);
        $this->add([
            'name'       => 'ponderation-service-compl',
            'options'    => [
                'label' => 'Ponderation service complémentaire',
            ],
            'attributes' => [
            ],
            'type'       => 'Text',
        ]);

        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);

        return $this;
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
            'code'                      => [
                'required' => false,
            ],
            'libelle'                   => [
                'required' => true,
            ],
            'ponderation-service-du'    => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'ponderation-service-compl' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
        ];
    }

}





class ModulateurHydrator implements HydratorInterface
{
    use TypeModulateurServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                             $data
     * @param \Paiement\Entity\Db\Modulateur $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)

    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setPonderationServiceDu(FloatFromString::run($data['ponderation-service-du']));
        $object->setPonderationServiceCompl(FloatFromString::run($data['ponderation-service-compl']));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Paiement\Entity\Db\modulateur $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                        => $object->getId(),
            'code'                      => $object->getCode(),
            'libelle'                   => $object->getLibelle(),
            'type-modulateur'           => $object->getTypeModulateur(),
            'ponderation-service-du'    => StringFromFloat::run($object->getPonderationServiceDu()),
            'ponderation-service-compl' => StringFromFloat::run($object->getPonderationServiceCompl()),
        ];

        return $data;
    }
}