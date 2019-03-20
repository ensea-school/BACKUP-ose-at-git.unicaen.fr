<?php

namespace Application\Form\Intervenant;

use Application\Entity\Db\ModificationServiceDu;
use Application\Form\AbstractFieldset;
use Application\Form\Intervenant\Traits\MotifModificationServiceDuFieldsetAwareTrait;
use Application\Entity\Db\Intervenant;
use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * Fieldset de saisie des modifications de service dû par un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see    FonctionModificationServiceDuFieldset
 */
class ModificationServiceDuFieldset extends AbstractFieldset
{
    use MotifModificationServiceDuFieldsetAwareTrait;



    public function init()
    {
        $this
            ->setHydrator(new ModificationServiceDuFieldsetHydrator())
            ->setObject(new Intervenant());

        $this->add([
            'type'    => 'Zend\Form\Element\Collection',
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
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
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
     * @param  Intervenant $intervenant
     *
     * @return array
     */
    public function extract($intervenant)
    {
        return [
            'modificationServiceDu' => $intervenant->getModificationServiceDu(),
        ];
    }



    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array       $data
     * @param  Intervenant $intervenant
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