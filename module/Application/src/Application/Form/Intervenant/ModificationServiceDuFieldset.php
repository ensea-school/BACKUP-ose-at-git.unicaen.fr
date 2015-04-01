<?php

namespace Application\Form\Intervenant;

use Zend\Form\Fieldset;
use Application\Entity\Db\IntervenantPermanent;

/**
 * Fieldset de saisie des modifications de service dû par un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see FonctionModificationServiceDuFieldset
 */
class ModificationServiceDuFieldset extends Fieldset implements \Zend\ServiceManager\ServiceLocatorAwareInterface
{
    use \Zend\ServiceManager\ServiceLocatorAwareTrait;

    public function init()
    {
        $this
                ->setHydrator(new ModificationServiceDuFieldsetHydrator())
                ->setObject(new IntervenantPermanent());

        $targetElement = $this->getServiceLocator()->get('IntervenantMotifModificationServiceDuFieldset');

        $this->add([
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'modificationServiceDu',
            'options' => [
//                'label' => 'Ajoutez autant de fonctions que nécessaire...',
                'count' => 0,
                'should_create_template' => true,
                'allow_add' => true,
                'allow_remove' => true,
                'target_element' => $targetElement,
            ],
        ]);
    }
}

class ModificationServiceDuFieldsetHydrator implements \Zend\Stdlib\Hydrator\HydratorInterface
{

    /**
     * Extract values from an object
     *
     * @param  IntervenantPermanent $intervenant
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
     * @param  array $data
     * @param  IntervenantPermanent $intervenant
     * @return IntervenantPermanent
     */
    public function hydrate(array $data, $intervenant)
    {
        $newModificationsServiceDu = $data['modificationServiceDu'];
        $curModificationsServiceDu = \Zend\Stdlib\ArrayUtils::iteratorToArray($intervenant->getModificationServiceDu());

        // historicisation des enregistrements supprimés
        $toRemove = array_diff($curModificationsServiceDu, $newModificationsServiceDu);
        foreach ($toRemove as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
            $modificationServiceDu->setHistoDestruction(new \DateTime());
        }

        // insertion des nouveaux enregistrements
        foreach ($newModificationsServiceDu as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
            if (null === $modificationServiceDu->getId()) {
                $intervenant->addModificationServiceDu($modificationServiceDu);
                $modificationServiceDu->setIntervenant($intervenant);
            }
        }

        return $intervenant;
    }
}