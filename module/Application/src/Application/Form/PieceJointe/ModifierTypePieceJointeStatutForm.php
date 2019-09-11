<?php

namespace Application\Form\PieceJointe;

use Application\Entity\Db\TypePieceJointeStatut;
use Application\Form\AbstractForm;
use Application\Service\Traits\ContextServiceAwareTrait;
use Application\Service\Traits\TypePieceJointeStatutServiceAwareTrait;
use Zend\Form\Element\Csrf;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;


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
            'name'    => 'premier-recrutement',
            'options' => [
                'label' => 'Uniquement en cas de premier recrutement',
            ],
            'type'    => 'Checkbox',
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
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'typePieceJointe'     => [
                'required' => true,
            ],
            'seuil-hetd'          => [
                'required'   => false,
                'validators' => [
                    [
                        'name'    => 'Zend\Validator\GreaterThan',
                        'options' => [
                            'min'       => 0,
                            'inclusive' => true,
                            'messages'  => [
                                \Zend\Validator\GreaterThan::NOT_GREATER => "Le nombre d'heures doit être supérieur à 0",
                            ],
                        ],
                    ],
                ],
            ],
            'changement-rib'      => [
                'required' => true,
            ],
            'premier-recrutement' => [
                'required' => true,
            ],
            'fc'                  => [
                'required' => true,
            ],
            'annee-debut'         => [
                'required' => false,
            ],
            'annee-fin'           => [
                'required' => false,
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
     * @param  array                                        $data
     * @param  \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setPremierRecrutement($data['premier-recrutement']);
        $object->setChangementRIB($data['changement-rib']);
        $object->setObligatoire($data['typePieceJointe']);
        $object->setSeuilHetd($data['seuil-hetd']);
        if (array_key_exists('annee-debut', $data)) {
            $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
        }
        if (array_key_exists('annee-fin', $data)) {
            $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
        }
        $object->setFC($data['fc']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypePieceJointeStatut $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                  => $object->getId(),
            'typePieceJointe'     => $object->getObligatoire(),
            'premier-recrutement' => $object->getPremierRecrutement(),
            'seuil-hetd'          => $object->getSeuilHeures(),
            'premier-recrutement' => $object->getPremierRecrutement(),
            'changement-rib'      => $object->getChangementRIB(),
            'fc'                  => $object->getFC(),
            'annee-debut'         => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'annee-fin'           => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
        ];

        return $data;
    }
}