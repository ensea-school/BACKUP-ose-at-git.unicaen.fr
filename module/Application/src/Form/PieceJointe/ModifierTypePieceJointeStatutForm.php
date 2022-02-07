<?php

namespace Application\Form\PieceJointe;

use Application\Entity\Db\TypePieceJointeStatut;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypePieceJointeStatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of ModifierTypePieceJointeSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
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
            'type'       => 'Select',
            'name'       => 'annee-debut',
            'options'    => [
                'label' => 'À partir de',
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
                'label' => 'Jusqu\'à',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
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
     * @param TypePieceJointeStatut $tpjs
     *
     * @return $this
     */
    public function buildAnnees(TypePieceJointeStatut $tpjs)
    {
        /* Limitations des années de début */
        $derniereAnneeDebut = $this->getServiceTypePieceJointeStatut()->derniereAnneeDebut($tpjs);
        if ($derniereAnneeDebut) {
            $this->get('annee-debut')->setValueOptions($this->getAnnees($derniereAnneeDebut, null));
            if (!$tpjs->getId()) {
                $tpjs->setAnneeDebut($this->getServiceAnnee()->getSuivante($derniereAnneeDebut));
            }
        } else {
            $this->get('annee-debut')->setValueOptions($this->getAnnees(null, null));
            $this->get('annee-debut')->setEmptyOption('Pas de limite');
        }


        /* Limitations des années de fin */
        $premiereAnneeFin = $this->getServiceTypePieceJointeStatut()->premiereAnneeFin($tpjs);
        if ($premiereAnneeFin) {
            $this->get('annee-fin')->setValueOptions($this->getAnnees(null, $premiereAnneeFin));
            if (!$tpjs->getId()) {
                $tpjs->setAnneeFin($this->getServiceAnnee()->getPrecedente($premiereAnneeFin));
            }
        } else {
            $this->get('annee-fin')->setValueOptions($this->getAnnees(null, null));
            $this->get('annee-fin')->setEmptyOption('Pas de limite');
        }

        return $this;
    }



    private function getAnnees($min, $max)
    {
        $annee = $this->getServiceContext()->getAnnee()->getId();
        $as    = $this->getServiceAnnee()->getList();

        $annees = [];
        foreach ($as as $ak => $av) {
            if ($ak >= $annee - 10 && $ak <= $annee + 10) {
                if ((!$min || $ak > $min->getId()) && (!$max || $ak < $max->getId())) {
                    $annees[$ak] = $av->getLibelle();
                }
            }
        }

        return $annees;
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
            'fc'              => [
                'required' => true,
            ],
            'obligatoire-hnp' => [
                'required' => true,
            ],
            'annee-debut'     => [
                'required' => false,
            ],
            'annee-fin'       => [
                'required' => false,
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
        $object->setTypeHeureHetd((empty($data['type-heure-hetd']) ? null : $data['type-heure-hetd']));

        if (array_key_exists('annee-debut', $data)) {
            $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
        }
        if (array_key_exists('annee-fin', $data)) {
            $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
        }
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
            'fc'              => $object->getFC(),
            'duree-vie'       => $object->getDureeVie(),
            'annee-debut'     => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'annee-fin'       => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
            'obligatoire-hnp' => $object->isObligatoireHNP(),
        ];

        return $data;
    }
}