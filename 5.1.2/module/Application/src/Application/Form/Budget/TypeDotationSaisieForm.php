<?php

namespace Application\Form\Budget;

use Application\Entity\Db\Traits\TypeRessourceAwareTrait;
use Application\Form\AbstractForm;
use Application\Service\Traits\TypeDotationServiceAwareTrait;
use Application\Service\Traits\TypeRessourceServiceAwareTrait;
use Zend\Form\Element\Csrf;
use Application\Service\Traits\ContextAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenImport\Entity\Db\Source;

    /**
     * Description of TypeDotationSaisieForm
     *
     * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
     */
    class TypeDotationSaisieForm extends AbstractForm
    {
        use TypeDotationServiceAwareTrait;
        use ContextAwareTrait;
        use TypeRessourceServiceAwareTrait;

        public function init()
        {
            $hydrator=new TypeDotationHydrator();
            $hydrator->setServiceTypeRessource($this->getServiceTypeRessource());
            $this->setHydrator($hydrator);

            $this->setAttribute('action', $this->getCurrentUrl());
            $this->add([
                'name' => 'id',
                'type' => 'hidden',
            ]);
            $this->add([
                'name' => 'libelle',
                'options' => [
                    'label' => "Libelle",
                ],
                'type' => 'Text'
            ]);
            $this->add([
                'name' => 'source-code',
                'options' => [
                    'label' => "Code",
                ],
                'type' => 'Text',
            ]);
            $this->add([
                'name' => 'type-ressource',
                'options' => [
                    'label' => 'Type de ressource',
                ],
                'type' => 'Select',
            ]);
            
            $this->add([
                'name' => 'submit',
                'type' => 'Submit',
                'attributes' => [
                    'value' => 'Enregistrer',
                    'class' => 'btn btn-primary',
                ],
            ]);

            // peuplement des types de ressource
            $this->get('type-ressource')
                ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceTypeRessource()->getList()));
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
                'libelle' => [
                    'required' => true,
                ],
                'source-code' => [
                    'required' => false,
                ],
                'type-ressource' => [
                'required' => true,
            ],
        ];
        }

    }



    class TypeDotationHydrator implements HydratorInterface
    {
        use TypeRessourceAwareTrait;
        use TypeRessourceServiceAwareTrait;

        /**
         * Hydrate $object with the provided $data.
         *
         * @param  array $data
         * @param  \Application\Entity\Db\TypeDotation $object
         *
         * @return object
         */
        public function hydrate(array $data, $object)
        {
            $object->setLibelle($data['libelle']);
            $object->setSourceCode($data['source-code']);
            $object->setSource($this->getTypeRessource());
            if (array_key_exists('type-ressource', $data)) {
                $object->setTypeRessource($this->getServiceTypeRessource()->get($data['type-ressource']));
            }
            return $object;
        }


        /**
         * Extract values from an object
         *
         * @param  \Application\Entity\Db\TypeDotation $object
         *
         * @return array
         */
        public function extract($object)
        {
            $data = [
                'id' => $object->getId(),
                'libelle' => $object->getLibelle(),
                'source-code' => $object->getSourceCode(),
                'type-ressource' => ($s = $object->getTypeRessource()) ? $s->getId() : null,
            ];

            return $data;
        }
    }