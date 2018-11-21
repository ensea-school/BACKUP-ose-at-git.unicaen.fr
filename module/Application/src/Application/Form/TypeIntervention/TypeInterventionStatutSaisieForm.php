<?php

namespace Application\Form\TypeInterventionStatut;

use Application\Form\AbstractForm;
use Application\Service\Traits\TypeInterventionStatutServiceAwareTrait;
use Application\Service\Traits\TypeIntervenantServiceAwareTrait;
use Zend\Form\Element\Csrf;
use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Util;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of TypeInterventionStatutStructureSaisieForm
 *
 * @author ZVENIGOROSKY Alexandre <alexandre.zvenigorosky at unicaen.fr>
 */
class TypeInterventionStatutStructureSaisieForm extends AbstractForm
{
    use \Application\Entity\Db\Traits\TypeInterventionStatutStructureAwareTrait;
    use TypeInterventionServiceAwareTrait;
    use StatutIntervenantServiceAwareTrait;



    public function init()
    {
        $hydrator = new TypeInterventionStatutStructureHydrator();
        $this->setHydrator($hydrator);

        $this->setAttribute('action', $this->getCurrentUrl());
        $this->add([
                'name' => 'type-intervention',
                'type' => 'Hidden',
            ]
        );
        $this->add([
            'name'       => 'statut-intervenant',
            'options'    => [
                'label' => 'Staut de l\'intervenant',
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
        $this->get('statutIntervenant')
            ->setValueOptions(\UnicaenApp\Util::collectionAsOptions($serviceStatutIntervenant->getList()));

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
            'stauut-intervenant' => [
                'required' => true,
            ],
        ];
    }

}





class TypeInterventionStatutStructureHydrator implements HydratorInterface
{
    use TypeInterventionIntervenantServiceAwareTrait;
    use TypeInterventionStatutServiceAwareTrait;
    use EntityManagerAwareTrait;



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array                                                  $data
     * @param  \Application\Entity\Db\TypeInterventionStatutStructure $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $object->setTypeIntervention($this->getServiceTypeIntervention()->get($data['type-intervention']));
        if (array_key_exists('sttaut-intervenant', $data)) {
            $object->setStatutIntervenant($this->getServiceStatutIntervenant()->get($data['statut-intervenant']));
        }
        $object->setTauxHETDService($data['taux-hetd-service']);
        $object->setTauxHETDComplemntaire(data['taux-statut-complementaire']);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\TypeInterventionStatutStructure $object
     *
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'id'                => $object->getId(),
            'type-intervention' => $object->getTypeIntervention(),
            'statut'         => ($s = $object->getStatutIntervenant()) ? $s->getId() : null,
            'taux-hetd-service'                => $object->getId(),
            'taux-hetd-complementaire'                => $object->getId(),
        ];

        return $data;
    }
}