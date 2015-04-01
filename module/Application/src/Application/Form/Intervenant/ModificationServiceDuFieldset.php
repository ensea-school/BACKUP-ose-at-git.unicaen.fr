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
     * @var \Application\Entity\Db\Annee
     */
    protected $annee;

    /**
     * Extract values from an object
     *
     * @param  IntervenantPermanent $intervenant
     * @return array
     */
    public function extract($intervenant)
    {
        if (!$this->annee) {
            throw new \Common\Exception\LogicException("Aucune année spécifiée.");
        }

        return [
            'modificationServiceDu' => $intervenant->getModificationServiceDu($this->annee),
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
        if (!$this->annee) {
            throw new \Common\Exception\LogicException("Aucune année spécifiée.");
        }

        $newModificationsServiceDu = $data['modificationServiceDu'];
        $curModificationsServiceDu = \Zend\Stdlib\ArrayUtils::iteratorToArray($intervenant->getModificationServiceDu($this->annee));

//        foreach ($newModificationsServiceDu as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
//            var_dump("SR posté : " . $modificationServiceDu . " {$modificationServiceDu->getId()} (Fonction {$modificationServiceDu->getFonction()->getId()})");
//        }
//        foreach ($curModificationsServiceDu as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
//            var_dump("SR existant : " . $modificationServiceDu . " {$modificationServiceDu->getId()} (Fonction {$modificationServiceDu->getFonction()->getId()})");
//        }

        // historicisation des enregistrements supprimés
        $toRemove = array_diff($curModificationsServiceDu, $newModificationsServiceDu);
        foreach ($toRemove as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
            $modificationServiceDu->setHistoDestruction(new \DateTime());
        }
//        foreach ($toRemove as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
//            var_dump("SR a suppr : " . $modificationServiceDu . " {$modificationServiceDu->getId()} (Fonction {$modificationServiceDu->getFonction()->getId()})");
//        }

        // insertion des nouveaux enregistrements
        foreach ($newModificationsServiceDu as $modificationServiceDu) { /* @var $modificationServiceDu \Application\Entity\Db\ModificationServiceDu */
            if (null === $modificationServiceDu->getId()) {
                $intervenant->addModificationServiceDu($modificationServiceDu);
                $modificationServiceDu
                        ->setIntervenant($intervenant)
                        ->setAnnee($this->annee);
//                var_dump("SR a ajout : " . $modificationServiceDu . " {$modificationServiceDu->getId()} (Fonction {$modificationServiceDu->getFonction()->getId()})");
            }
        }

        return $intervenant;
    }

    /**
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return self
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee)
    {
        $this->annee = $annee;

        return $this;
    }
}