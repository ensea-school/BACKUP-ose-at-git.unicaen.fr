<?php

namespace Application\Form\FormuleTest;

use Application\Entity\Db\FormuleTestIntervenant;
use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Application\Form\AbstractForm;
use Zend\Stdlib\Hydrator\HydratorInterface;


/**
 * Description of IntervenantForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class IntervenantForm extends AbstractForm
{

    public function init()
    {
        $hydrator = new IntervenantFormHydrator;
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());

        $this->add([
            'name'    => 'libelle',
            'options' => [
                'label' => "Libelle",
            ],
            'type'    => 'Text',
        ]);

        $this->add([
            'name'       => 'heuresDecharge',
            'options'    => [
                'label' => 'Heures de décharge',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'heuresServiceStatutaire',
            'options'    => [
                'label' => 'Heures de service statutaire',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'heuresServiceModifie',
            'options'    => [
                'label' => 'Heures de modification de service',
            ],
            'type'       => 'Text',
        ]);

        $this->add([
            'name'       => 'depassementServiceDuSansHC',
            'options'    => [
                'label' => 'Le dépassement de service dû ne doit pas donner lieu à des heures complémentaires',
                'value_options' => [
                    'false' => 'Non',
                    'true' => 'Oui',
                ],
            ],
            'type'       => 'Select',
        ]);

        for( $i = 1;$i<=5;$i++) {
            $this->add([
                'name'       => 'param'.$i,
                'options'    => [
                    'label' => 'Paramètre '.$i,
                ],
                'type'       => 'Text',
            ]);
        }

        $this->add([
            'name'       => 'submit',
            'type'       => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);
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
            /* Filtres et validateurs */
        ];
    }
}





class IntervenantFormHydrator implements HydratorInterface
{

    /**
     * @param  array                  $data
     * @param  FormuleTestIntervenant $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setHeuresDecharge(FloatFromString::run($data['heuresDecharge']));
        $object->setHeuresServiceStatutaire(FloatFromString::run($data['heuresServiceStatutaire']));
        $object->setHeuresServiceModifie(FloatFromString::run($data['heuresServiceModifie']));
        $object->setDepassementServiceDuSansHC($data['depassementServiceDuSansHC'] == 'true');
        for( $i = 1;$i<=5;$i++) {
            $object->{'setParam'.$i}($data['param'.$i]);
        }

        return $object;
    }



    /**
     * @param FormuleTestIntervenant $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'                 => $object->getLibelle(),
            'heuresDecharge'          => StringFromFloat::run($object->getHeuresDecharge()),
            'heuresServiceStatutaire' => StringFromFloat::run($object->getHeuresServiceStatutaire()),
            'heuresServiceModifie'    => StringFromFloat::run($object->getHeuresServiceModifie()),
            'depassementServiceDuSansHC' => $object->isDepassementServiceDuSansHC() ? 'true' : 'false',
        ];
        for( $i = 1;$i<=5;$i++) {
            $data['param'.$i] = $object->{'getParam'.$i}();
        }

        return $data;
    }
}