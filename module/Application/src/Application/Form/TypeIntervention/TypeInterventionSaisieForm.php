<?php
namespace Application\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\AnneeAwareTrait;
use UnicaenApp\Util;

/**
 * Description of TypeInterventionSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionAwareTrait;
    use AnneeAwareTrait;

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
            'name' => 'visible',
            'options' => [
                'label' => 'Actif ?',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-debut-id',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'année de début',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-fin-id',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getList()),
                'label'         => 'année de fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name' => 'regle-foad',
            'options' => [
                'label' => 'limité à la FOAD',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'regle-fc',
            'options' => [
                'label' => 'limité à la FC',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'regle-chargens',
            'options' => [
                'label' => 'nécessite une charge d\'enseignement',
            ],
            'type' => 'Checkbox',
        ]);

        $this->add([
            'name' => 'regle-vh-ens',
            'options' => [
                'label' => 'nécessite des heures saisie dans la maquette',
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
            'annee-debut-id' => [
                'required' => false,
            ],
            'annee-fin-id'   => [
                'required' => false,
            ],
            'regle-foad' => [
                'required' => true,
            ],
            'regle-fc' => [
                'required' => true,
            ],
            'regle-chargens' => [
                'required' => true,
            ],
            'regle-vh-ens' => [
                'required' => true,
            ],
        ];
    }

}

class TypeInterventionHydrator implements HydratorInterface
{
    use TypeInterventionAwareTrait;
    use AnneeAwareTrait;

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
        if (array_key_exists('annee-debut-id', $data)) {
            $object->setAnneeDebutId($this->getServiceAnnee()->get($data['annee-debut-id']));
        }
        if (array_key_exists('annee-fin-id', $data)) {
            $object->setAnneeFinId($this->getServiceAnnee()->get($data['annee-fin-id']));
        }
        $object->setRegleFOAD($data['regle-foad']);
        $object->setRegleFC($data['regle-fc']);
        $object->setRegleChargens($data['regle-chargens']);
        $object->setRegleVHEns($data['regle-vh-ens']);
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
            'annee-debut-id'       => $object->getAnneeDebutId() ? $object->getAnneeDebutId()->getId() : null,
            'annee-fin-id'         => $object->getAnneeFinId() ? $object->getAnneeFinId()->getId() : null,
            'regle-foad'           => $object->getRegleFOAD(),
            'regle-fc'           => $object->getRegleFC(),
            'regle-chargens'           => $object->getRegleChargens(),
            'regle-vh-ens'           => $object->getRegleVHEns(),
        ];

        return $data;
    }
}
