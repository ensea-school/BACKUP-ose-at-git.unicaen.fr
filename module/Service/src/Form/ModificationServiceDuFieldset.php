<?php

namespace Service\Form;

use Service\Entity\Db\ModificationServiceDu;
use Application\Form\AbstractFieldset;
use Application\Entity\Db\Intervenant;
use Laminas\Stdlib\ArrayUtils;
use Laminas\Hydrator\HydratorInterface;

class ModificationServiceDuFieldset extends AbstractFieldset
{
    use MotifModificationServiceDuFieldsetAwareTrait;


    public function init()
    {
        $this
            ->setHydrator(new ModificationServiceDuFieldsetHydrator())
            ->setObject(new Intervenant());

        $this->add([
            'type'    => 'Laminas\Form\Element\Collection',
            'name'    => 'modificationServiceDu',
            'options' => [
                'count'                  => 0,
                'should_create_template' => true,
                'allow_add'              => true,
                'allow_remove'           => true,
                'target_element'         => $this->getFieldsetIntervenantMotifModificationServiceDu(),
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
        return [];
    }

}





class ModificationServiceDuFieldsetHydrator implements HydratorInterface
{

    /**
     * Extract values from an object
     *
     * @param Intervenant $intervenant
     *
     * @return array
     */
    public function extract($intervenant): array
    {
        return [
            'modificationServiceDu' => $intervenant->getModificationServiceDu(),
        ];
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param array       $data
     * @param Intervenant $intervenant
     *
     * @return Intervenant
     */
    public function hydrate(array $data, $intervenant)
    {
        $newModificationsServiceDu = $data['modificationServiceDu'];
        $curModificationsServiceDu = ArrayUtils::iteratorToArray($intervenant->getModificationServiceDu());

        // historicisation des enregistrements supprimés
        $toRemove = array_diff($curModificationsServiceDu, $newModificationsServiceDu);
        foreach ($toRemove as $modificationServiceDu) {
            /* @var $modificationServiceDu ModificationServiceDu */
            $modificationServiceDu->setHistoDestruction(new \DateTime());
        }

        // insertion des nouveaux enregistrements
        foreach ($newModificationsServiceDu as $modificationServiceDu) {
            /* @var $modificationServiceDu ModificationServiceDu */
            if (null === $modificationServiceDu->getId()) {
                $intervenant->addModificationServiceDu($modificationServiceDu);
                $modificationServiceDu->setIntervenant($intervenant);
            }
        }

        return $intervenant;
    }
}