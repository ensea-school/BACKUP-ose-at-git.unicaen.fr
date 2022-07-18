<?php

namespace Application\Form\TypeIntervention;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Laminas\Hydrator\HydratorInterface;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\AnneeServiceAwareTrait;
use UnicaenApp\Util;

/**
 * Description of TypeInterventionSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionAwareTrait;
    use AnneeServiceAwareTrait;

    public function init()
    {
        $hydrator = new TypeInterventionHydrator();
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
            'name'    => 'libelle',
            'options' => [
                'label' => "Libellé",
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'ordre',
            'options' => [
                'label' => "",
            ],
            'type'    => 'hidden',
        ]);
        $this->add([
            'name'    => 'taux-hetd-service',
            'options' => [
                'label' => 'Taux Hetd Service',
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'taux-hetd-complementaire',
            'options' => [
                'label' => 'Taux Hetd Complémentaire',
            ],
            'type'    => 'Text',
        ]);
        $this->add([
            'name'    => 'visible',
            'options' => [
                'label' => 'Visible ?',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'visible-exterieur',
            'options' => [
                'label' => 'Visible de l\'extérieur?',
            ],
            'type'    => 'Checkbox',
        ]);
        $this->add([
            'type'       => 'Select',
            'name'       => 'annee-debut',
            'options'    => [
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
                'label'         => 'Année de début',
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
                'empty_option'  => 'Aucune',
                'value_options' => Util::collectionAsOptions($this->getServiceAnnee()->getChoixAnnees()),
                'label'         => 'Année de fin',
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
        ]);

        $this->add([
            'name'    => 'regle-foad',
            'options' => [
                'label' => 'Limité à la FOAD',
            ],
            'type'    => 'Checkbox',
        ]);

        $this->add([
            'name'    => 'regle-fc',
            'options' => [
                'label' => 'Limité à la FC',
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



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'code'                     => [
                'required' => true,
            ],
            'libelle'                  => [
                'required' => true,
            ],
            'taux-hetd-service'        => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return FloatFromString::run($value) >= 0.0;
                        }]),
                ],
            ],
            'taux-hetd-complementaire' => [
                'required'   => true,
                'validators' => [
                    new \Laminas\Validator\Callback([
                        'messages' => [\Laminas\Validator\Callback::INVALID_VALUE => '%value% doit être >= 0'],
                        'callback' => function ($value) {
                            return (FloatFromString::run($value)) >= 0.0;
                        }]),
                ],
            ],
            'annee-debut'              => [
                'required' => false,
            ],
            'annee-fin'                => [
                'required' => false,
            ],
            'regle-foad'               => [
                'required' => true,
            ],
            'regle-fc'                 => [
                'required' => true,
            ],
            'visible-exterieur'        => [
                'equired' => true,
            ],
        ];
    }

}





class TypeInterventionHydrator implements HydratorInterface
{
    use TypeInterventionServiceAwareTrait;
    use AnneeServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                   $data
     * @param \Application\Entity\Db\TypeIntervention $object
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
        $object->setVisibleExterieur($data['visible-exterieur']);
        if (array_key_exists('annee-debut', $data)) {
            $object->setAnneeDebut($this->getServiceAnnee()->get($data['annee-debut']));
        }
        if (array_key_exists('annee-fin', $data)) {
            $object->setAnneeFin($this->getServiceAnnee()->get($data['annee-fin']));
        }
        $object->setRegleFOAD($data['regle-foad']);
        $object->setRegleFC($data['regle-fc']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Application\Entity\Db\TypeIntervention $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'id'                       => $object->getId(),
            'code'                     => $object->getCode(),
            'libelle'                  => $object->getLibelle(),
            'ordre'                    => $object->getOrdre(),
            'taux-hetd-service'        => StringFromFloat::run($object->getTauxHetdService()),
            'taux-hetd-complementaire' => StringFromFloat::run($object->getTauxHetdComplementaire()),
            'visible'                  => $object->isVisible(),
            'visible-exterieur'        => $object->isVisibleExterieur(),
            'annee-debut'              => $object->getAnneeDebut() ? $object->getAnneeDebut()->getId() : null,
            'annee-fin'                => $object->getAnneeFin() ? $object->getAnneeFin()->getId() : null,
            'regle-foad'               => $object->getRegleFOAD(),
            'regle-fc'                 => $object->getRegleFC(),
        ];

        return $data;
    }
}
