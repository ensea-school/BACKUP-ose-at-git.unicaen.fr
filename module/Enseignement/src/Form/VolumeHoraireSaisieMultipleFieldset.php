<?php

namespace Enseignement\Form;

use Enseignement\Entity\VolumeHoraireListe;
use Application\Filter\FloatFromString;
use Application\Form\AbstractFieldset;
use Application\Service\Traits\TypeInterventionServiceAwareTrait;
use Laminas\Form\Element\Hidden;
use Application\Filter\StringFromFloat;
use Laminas\Hydrator\HydratorInterface;
use Enseignement\Entity\Db\Service;
use UnicaenApp\Service\EntityManagerAwareTrait;


/**
 * Description of SaisieMultiple
 *
 * Permet de saisie tous les volumes horaires d'une période en même temps
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireSaisieMultipleFieldset extends AbstractFieldset
{
    use TypeInterventionServiceAwareTrait;

    /**
     *
     * @return \Application\Entity\Db\TypeIntervention[]
     */
    public function getTypesInterventions()
    {
        $qb = $this->getServiceTypeIntervention()->finderByContext();
        $this->getServiceTypeIntervention()->finderByHistorique($qb);

        return $this->getServiceTypeIntervention()->getList($qb);
    }



    /**
     *
     */
    public function init()
    {
        $hydrator = new SaisieMultipleHydrator;
        $hydrator->setServiceTypeIntervention($this->getServiceTypeIntervention());
        $hydrator->setEntityManager($this->getEntityManager());

        $this->setAttribute('method', 'post')
            ->setAttribute('class', 'volume-horaire-multiple')
            ->setHydrator($hydrator)
            ->setAllowedObjectBindingClass(VolumeHoraireListe::class);

        $tis = $this->getTypesInterventions();
        foreach ($tis as $typeIntervention) {
            $this->add([
                'name'       => $typeIntervention->getCode(),
                'options'    => [
                    'label'         => '<abbr title="' . $typeIntervention->getLibelle() . '">' . $typeIntervention->getCode() . '</abbr> :',
                    'label_options' => ['disable_html_escape' => true],
                ],
                'attributes' => [
                    'title' => $typeIntervention->getLibelle(),
                    'class' => 'volume-horaire volume-horaire-heures input-sm',
                    'step'  => 'any',
                    'min'   => 0,
                ],
                'type'       => 'Text',
            ]);
        }
        $this->add(new Hidden('type-volume-horaire'));
        $this->add(new Hidden('service'));
        $this->add(new Hidden('periode'));
    }



    /**
     * Should return an array specification compatible with
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        $filters = [];
        foreach ($this->getTypesInterventions() as $typeIntervention) {
            $filters[$typeIntervention->getCode()] = [
                'required' => false,
                'filters'  => [
                    ['name' => FloatFromString::class],
                ],
            ];
        }

        return $filters;
    }

}





/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieMultipleHydrator implements HydratorInterface
{
    use EntityManagerAwareTrait;
    use TypeInterventionServiceAwareTrait;


    /**
     *
     * @return \Application\Service\TypeInterventionService[]
     */
    public function getTypesInterventions(Service $service)
    {
        if ($service->getElementPedagogique()) {
            return $service->getElementPedagogique()->getTypeIntervention();
        } else {
            $qb = $this->getServiceTypeIntervention()->finderByHistorique();
            $this->getServiceTypeIntervention()->finderByContext($qb);

            return $this->getServiceTypeIntervention()->getList($qb);
        }
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array                                   $data
     * @param \Enseignement\Entity\VolumeHoraireListe $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $typeVolumeHoraire = $this->getEntityManager()->find(\Service\Entity\Db\TypeVolumeHoraire::class, (int)$data['type-volume-horaire']);
        $periode           = $this->getEntityManager()->find(\Application\Entity\Db\Periode::class, (int)$data['periode']);

        $object->setTypeVolumeHoraire($typeVolumeHoraire);
        $object->setPeriode($periode);
        $tis = $this->getTypesInterventions($object->getService());
        foreach ($tis as $typeIntervention) {
            $object->setTypeIntervention($typeIntervention);
            if (isset($data[$typeIntervention->getCode()])) {
                $heures = FloatFromString::run($data[$typeIntervention->getCode()]);
            } else {
                $heures = 0;
            }
            $object->setHeures($heures);
        }

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \Enseignement\Entity\VolumeHoraireListe $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $vhl  = $object->createChild();
        $data = [
            'type-volume-horaire' => $object->getTypeVolumeHoraire() ? $object->getTypeVolumeHoraire()->getId() : null,
            'service'             => $object->getService() ? $object->getService()->getId() : null,
            'periode'             => $object->getPeriode() ? $object->getPeriode()->getId() : null,
        ];
        $tis  = $this->getTypesInterventions($object->getService());
        foreach ($tis as $typeIntervention) {
            $vhl->setTypeIntervention($typeIntervention);
            $data[$typeIntervention->getCode()] = StringFromFloat::run($vhl->getHeures(), false);
        }

        return $data;
    }

}