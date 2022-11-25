<?php

namespace Application\Form\TypeIntervention;

use Application\Entity\Db\Traits\TypeInterventionStatutAwareTrait;
use Application\Form\AbstractForm;
use Intervenant\Service\StatutServiceAwareTrait;
use Laminas\Form\Element\Csrf;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;

/**
 * Description of TypeInterventionStatutDeleteForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStatutDeleteForm extends AbstractForm
{
    use TypeInterventionStatutAwareTrait;
    use StatutServiceAwareTrait;


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

        $this->add(new Csrf('security'));
        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => "Enregistrer",
                'class' => 'btn btn-primary',
            ],
        ]);
//        $this->get('statut')
//            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($this->getServiceStatut()->getList($this->getServiceStatut()->finderByHistorique())));

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
//            'statut' => [
//                'required' => true,
//            ],
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



