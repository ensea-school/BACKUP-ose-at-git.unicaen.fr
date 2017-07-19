<?php
namespace Application\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of TypeInterventionSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionAwareTrait;

    public function init()
    {
        $hydrator = new TypeInterventionHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
            'name' => 'code',
            'options' => [
                'label' => "Code",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle",
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'ordre',
            'options' => [
                'label' => "Ordre",
            ],
            'type' => 'Number',
        ]);
        $this->add([
            'name' => 'taux-hetd-service',
            'options' => [
                'label' => 'Taux Hetd Service',
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'taux-hetd-complementaire',
            'options' => [
                'label' => 'Taux Hetd Complémentaire',
            ],
            'type' => 'Text',
        ]);
        $this->add([
            'name' => 'personnalise',
            'options' => [
                'label' => 'Personnalisé ?',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'visible',
            'options' => [
                'label' => 'Visible ?',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add([
            'name' => 'enseignement',
            'options' => [
                'label' => 'Enseignement ?',
            ],
            'type' => 'Checkbox',
        ]);
        $this->add(new Csrf('security'));
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary'
            ],
        ]);
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
            'code' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
            'taux-hetd-service' => [
                'required' => true,
                'validators' => [
                    new \Zend\Validator\Callback(array(
                        'messages' => array(\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'),
                        'callback' => function ($value) {
                           return (FloatFromString::run($value) >= 0.0 ? true : false);
                        }))
                ],
            ],
            'taux-hetd-complementaire' => [
                'required' => true,
                'validators' => [
                    new \Zend\Validator\Callback(array(
                        'messages' => array(\Zend\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'),
                        'callback' => function ($value) {
                            return (StringFromFloat::run($value) >= 0.0 ? true : false);
                        }))
                ],
            ],
        ];
    }

}

class TypeInterventionHydrator implements HydratorInterface
{
    use TypeInterventionAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\TypeIntervention $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        $object->setOrdre($data['ordre']);
        $object->setTauxHetdService(FloatFromString::run($data['taux-hetd-service']));
        $object->setTauxHetdComplementaire(FloatFromString::run($data['taux-hetd-complementaire']));
        $object->setVisible($data['visible']);
        $object->setInterventionIndividualisee($data['personnalise']);
        $object->setEnseignement($data['enseignement']);
        return $object;
    }


    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypeIntervention $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id' => $object->getId(),
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'ordre' => $object->getOrdre(),
            'taux-hetd-service' => StringFromFloat::run($object->getTauxHetdService()),
            'taux-hetd-complementaire' => StringFromFloat::run($object->getTauxHetdComplementaire()),
            'visible' => $object->isVisible(),
            'personnalise' => $object->getInterventionIndividualisee(),
            'enseignement' => $object->getEnseignement()
        ];

        return $data;
    }
}
