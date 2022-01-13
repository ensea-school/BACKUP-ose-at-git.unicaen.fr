<?php

namespace Plafond\Form;

use Plafond\Entity\Db\PlafondApplication;
use Application\Form\AbstractForm;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Application\Service\Traits\ContextServiceAwareTrait;
use Plafond\Entity\Db\PlafondEtat;
use Plafond\Service\PlafondApplicationServiceAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use UnicaenApp\Util;
use Laminas\Hydrator\HydratorInterface;


/**
 * Description of PlafondApplicationForm
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PlafondApplicationForm extends AbstractForm
{
    use AnneeServiceAwareTrait;
    use PlafondServiceAwareTrait;
    use ContextServiceAwareTrait;
    use PlafondApplicationServiceAwareTrait;


    public function init()
    {
        $this->setAttribute('action', $this->getCurrentUrl());

        $hydrator = new PlafondApplicationFormHydrator;
        $hydrator->setServiceAnnee($this->getServiceAnnee());
        $hydrator->setServicePlafond($this->getServicePlafond());
        $this->setHydrator($hydrator);

        $this->add([
            'type' => 'Hidden',
            'name' => 'plafond',
        ]);

        $this->add([
            'type' => 'Hidden',
            'name' => 'typeVolumeHoraire',
        ]);

        $this->add([
            'name'       => 'plafondEtat',
            'options'    => [
                'label'         => 'État',
                'value_options' => Util::collectionAsOptions($this->getServicePlafond()->getEtats()),
            ],
            'attributes' => [
                'class'            => 'selectpicker',
                'data-live-search' => 'true',
            ],
            'type'       => 'Select',
        ]);

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
     * {@link Laminas\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'plafondEtat'       => ['required' => true],
            'plafond'           => ['required' => true],
            'typeVolumeHoraire' => ['required' => true],
        ];
    }

}





class PlafondApplicationFormHydrator implements HydratorInterface
{
    use AnneeServiceAwareTrait;
    use PlafondServiceAwareTrait;


    /**
     * @param array              $data
     * @param PlafondApplication $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $plafondEtat = $this->getServicePlafond()->getEntityManager()->find(PlafondEtat::class, $data['plafondEtat']);

        $object->setPlafondEtat($plafondEtat);

        return $object;
    }



    /**
     * @param PlafondApplication $object
     *
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'plafondEtat'       => $object->getPlafondEtat() ? $object->getPlafondEtat()->getId() : null,
            'plafond'           => $object->getPlafond()->getId(),
            'typeVolumeHoraire' => $object->getTypeVolumeHoraire()->getId(),
        ];

        return $data;
    }
}