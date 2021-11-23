<?php

namespace Application\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionStatutServiceAwareTrait;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of TypeInterventionStatutSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStatutSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionStatutAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;


    public function init()
    {
        $hydrator = new TypeInterventionStatutHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-intervention',
                'type' => 'hidden',
            ]
        );
        $this->add([
            'name'       => 'statut-intervenant',
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
                'label' => "Taux HETD service",
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
        $this->get('statut-intervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceStatutIntervenant()->getList($this->getServiceStatutIntervenant()->finderByHistorique())));

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
            'statut-intervenant'       => [
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
                            return (StringFromFloat::run($value) >= 0.0 ? true : false);
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
    use StatutIntervenantServiceAwareTrait;
    use TypeInterventionStatutServiceAwareTrait;
    use EntityManagerAwareTrait;


    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                         $data
     * @param \Application\Entity\Db\TypeInterventionStatut $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setTypeIntervention($this->getServiceTypeIntervention()->get($data['type-intervention']));
        if (array_key_exists('statut-intervenant', $data)) {
            $object->setStatutIntervenant($this->getServiceStatutIntervenant()->get($data['statut-intervenant']));
        }
        $object->setTauxHETDService(FloatFromString::run($data['taux-hetd-service']));
        $object->setTauxHETDComplementaire(FloatFromString::run($data['taux-hetd-complementaire']));

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\TypeInterventionStatut $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                       => $object->getId(),
            'type-intervention'        => $object->getTypeIntervention()->getId(),
            'statut-intervenant'       => ($s = $object->getStatutIntervenant()) ? $s->getId() : null,
            'taux-hetd-service'        => StringFromFloat::run($object->getTauxHETDService()),
            'taux-hetd-complementaire' => StringFromFloat::run($object->getTauxHETDComplementaire()),
        ];

        return $data;
    }
}