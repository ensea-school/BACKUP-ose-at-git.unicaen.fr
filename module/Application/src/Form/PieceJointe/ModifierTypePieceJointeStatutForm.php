<?php

namespace Application\Form\PieceJointe;

use Application\Entity\Db\TypePieceJointeStatut;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypePieceJointeStatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;


class ModifierTypePieceJointeStatutForm extends AbstractForm
{
    use AnneeServiceAwareTrait;
    use ContextServiceAwareTrait;
    use TypePieceJointeStatutServiceAwareTrait;


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
            'type'    => 'Checkbox',
            'name'    => 'typePieceJointe',
            'options' => [
                'label' => "La pièce justifitative doit être fournie obligatoirement",
            ],
        ]);

        $this->add([
            'name'       => 'seuil-hetd',
            'options'    => [
                'label' => "Nombre d'heures min.",
            ],
            'type'       => 'Number',
            'attributes' => [
                'min' => '0',
            ],
        ]);

        $this->add([
            'name'    => 'type-heure-hetd',
            'options' => [
                'label' => 'Calculer les seuils en utilisant les heures  en équivalent HETD',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'changement-rib',
            'options' => [
                'label' => 'Uniquement en cas de changement de RIB',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'fc',
            'options' => [
                'label' => 'Limité aux actions de formation continue',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'type'       => 'Number',
            'name'       => 'duree-vie',
            'options'    => [
                'label' => "Durée de vie de la pièce jointe (en année)",
            ],
            'attributes' => [
                'min'       => '1',
                'value'     => '1',
                'class'     => 'form-control',
                'info_icon' => "Si vous avez coché 'Uniquement en cas de changement de RIB', la durée de vie sera automatiquement à 1",
            ],
        ]);

        $this->add([
            'type'    => 'Checkbox',
            'name'    => 'obligatoire-hnp',
            'options' => [
                'label' => "Pièce jointe obligatoire même si les heures sont non payables",
            ],
        ]);

        $this->add(new Csrf('security'));

        $this->addSubmit();

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
            'typePieceJointe' => [
                'required' => true,
            ],
            'seuil-hetd'      => [
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'Laminas\Validator\GreaterThan',
                        'options' => [
                            'min'       => 0,
                            'inclusive' => true,
                            'messages'  => [
                                \Laminas\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
            'changement-rib'  => [
                'required' => true,
            ],
            'type-heure-hetd' => [
                'required' => true,
            ],
            'fc'              => [
                'required' => true,
            ],
            'obligatoire-hnp' => [
                'required' => true,
            ],
            'duree-vie'       => [
                'required' => true,
            ],
        ];
    }

}





class TypePieceJointeStatutHydrator implements HydratorInterface
{
    use AnneeServiceAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                        $data
     * @param \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {

        $object->setChangementRIB($data['changement-rib']);
        $object->setObligatoire($data['typePieceJointe']);
        $object->setSeuilHetd((empty($data['seuil-hetd']) ? null : $data['seuil-hetd']));
        $object->setTypeHeureHetd($data['type-heure-hetd']);
        $object->setFC($data['fc']);
        $object->setDureeVie($data['duree-vie']);
        $object->setObligatoireHNP($data['obligatoire-hnp']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'              => $object->getId(),
            'typePieceJointe' => $object->getObligatoire(),
            'seuil-hetd'      => $object->getSeuilHetd(),
            'type-heure-hetd' => $object->getTypeHeureHetd(),
            'changement-rib'  => $object->getChangementRIB(),
            'fc'              => $object->getFc(),
            'duree-vie'       => $object->getDureeVie(),
            'obligatoire-hnp' => $object->getObligatoireHNP(),
        ];

        return $data;
    }
}