<?php

namespace Application\Form\Structure;

use Application\Entity\Db\Structure;
use Application\Form\AbstractForm;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenImport\Service\Traits\SchemaServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Form\FormInterface;
use Laminas\Hydrator\HydratorInterface;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of StructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky@unicaen.fr>
 */
class StructureSaisieForm extends AbstractForm implements EntityManagerAwareInterface
{
    use EntityManagerAwareTrait;
    use SourceServiceAwareTrait;
    use SchemaServiceAwareTrait;


    public function init()
    {
        $hydrator = new StructureHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name'    => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelleCourt',
            'options' => [
                'label' => "Libellé Court",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'libelleLong',
            'options' => [
                'label' => "Libellé Long",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'enseignement',
            'options' => [
                'label' => "Peut porter des enseignements",
            ],
            'type'    => 'Checkbox',
        ]);
        $this->add([
            'name'    => 'affAdresseContrat',
            'options' => [
                'label' => "Affichage de l'adresse sur le contrat de travail",
            ],
            'type'    => 'Checkbox',
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



    public function bind($object, $flags = FormInterface::VALUES_NORMALIZED)
    {
        /* @var $object Structure */
        parent::bind($object, $flags);

        if ($object->getSource() && $object->getSource()->getImportable()) {
            foreach ($this->getElements() as $element) {
                if ($this->getServiceSchema()->isImportedProperty($object, $element->getName())) {
                    $element->setAttribute('readonly', true);
                }
            }
        }

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
            'code' => [
                'required' => true,
            ],

            'libelleCourt' => [
                'required' => true,
            ],

            'libelleLong' => [
                'required' => true,
            ],

            'enseignement'      => [
                'required' => true,
            ],
            'affAdresseContrat' => [
                'required' => true,
            ],
        ];
    }

}





class StructureHydrator implements HydratorInterface
{
    use SourceServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                            $data
     * @param \Application\Entity\Db\Structure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelleCourt($data['libelleCourt']);
        $object->setLibelleLong($data['libelleLong']);
        $object->setEnseignement($data['enseignement']);
        $object->setAffAdresseContrat($data['affAdresseContrat']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\Structure $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                => $object->getId(),
            'code'              => $object->getCode(),
            'libelleCourt'      => $object->getLibelleCourt(),
            'libelleLong'       => $object->getLibelleLong(),
            'enseignement'      => $object->isEnseignement(),
            'affAdresseContrat' => $object->isAffAdresseContrat(),
        ];

        return $data;
    }
}   
    
