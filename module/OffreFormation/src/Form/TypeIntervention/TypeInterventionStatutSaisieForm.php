<?php

namespace OffreFormation\Form\TypeIntervention;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use OffreFormation\Service\Traits\TypeInterventionServiceAwareTrait;
use OffreFormation\Entity\Db\Traits\TypeInterventionStatutAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of TypeInterventionStatutSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStatutSaisieForm extends AbstractForm
{
    use TypeInterventionStatutAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use StatutServiceAwareTrait;


    public function init()
    {
        $hydrator = new TypeInterventionStatutHydrator();
        $this->setHydrator($hydrator);
        $hydrator->setEntityManager($this->getEntityManager());

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-intervention',
                'type' => 'hidden',
            ]
        );
        $this->add([
            'name'       => 'statut',
            'options'    => [
                'label' => 'Statut de l\'intervenant',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);
        $this->add([
            'name'    => 'taux-hetd-service',
            'options' => [
                'label' => "Taux HETD pour les heures de service",
            ],
            'type'    => 'text',
        ]);
        $this->add([
            'name'    => 'taux-hetd-complementaire',
            'options' => [
                'label' => "Taux HETD complementaire",
            ],
            'type'    => 'text',
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
        $this->get('statut')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceStatut()->getStatuts()));

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
            'statut'                   => [
                'required' => true,
            ],
            'taux-hetd-service'        => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'taux-hetd-complementaire' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }]),
                ],
            ],
            'annee-debut'              => [
                'required' => false,
            ],
        ];
    }

}





class TypeInterventionStatutHydrator implements HydratorInterface
{
    use TypeInterventionServiceAwareTrait;
    use StatutServiceAwareTrait;
    use EntityManagerAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                                $data
     * @param \OffreFormation\Entity\Db\TypeInterventionStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setTypeIntervention($this->getServiceTypeIntervention()->get($data['type-intervention']));
        if (array_key_exists('statut', $data)) {
            $object->setStatut($this->getServiceStatut()->get($data['statut']));
        }
        $object->setTauxHETDService(FloatFromString::run($data['taux-hetd-service']));
        $object->setTauxHETDComplementaire(FloatFromString::run($data['taux-hetd-complementaire']));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \OffreFormation\Entity\Db\TypeInterventionStatut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                       => $object->getId(),
            'type-intervention'        => $object->getTypeIntervention()->getId(),
            'statut'                   => ($s = $object->getStatut()) ? $s->getId() : null,
            'taux-hetd-service'        => StringFromFloat::run($object->getTauxHETDService()),
            'taux-hetd-complementaire' => StringFromFloat::run($object->getTauxHETDComplementaire()),
        ];

        return $data;
    }
}