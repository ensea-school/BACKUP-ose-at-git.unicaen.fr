<?php
namespace Application\Form\OffreFormation;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Entity\Db\ElementPedagogique;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ElementModulateursHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  ElementPedagogique $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $old = array();
        $new = array();

        /* Détection des anciennes valeurs */
        $oldEm = array();
        foreach( $object->getElementModulateur() as $elementModulateur ){
            $modulateur = $elementModulateur->getModulateur();
            $old[] = (int)$modulateur->getId();
            $oldEm[(int)$modulateur->getId()] = $elementModulateur;
        }
        /* Détection des nouvelles valeurs */
        foreach( $data as $typeModulateurCode => $modulateurId ){
            if ($modulateurId){
                $new[] = (int)$modulateurId;
            }
        }

        /* Détermination des changements à effectuer */
        sort($old);
        sort($new);
        $insert = array_diff($new,$old);
        $delete = array_diff($old, $new);

        /* Suppressions */
        foreach( $delete as $mid ){
            $oldEm[$mid]->setRemove(true); // Flag de suppression pour les anciens à retirer
            $object->setHasChanged(true);
        }
        /* Créations */
        foreach( $insert as $mid ){
            $elementModulateur = $this->getServiceLocator()->get('applicationElementModulateur')->newEntity();
            $elementModulateur->setElement($object);
            $elementModulateur->setModulateur( $this->getServiceLocator()->get('applicationModulateur')->get($mid) );
            $object->addElementModulateur($elementModulateur);
            $object->setHasChanged(true);
        }
        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  ElementPedagogique $object
     * @return array
     */
    public function extract($object)
    {
        $sm   = $this->getServiceLocator()->get('applicationModulateur');
        /* @var $sm \Application\Service\Modulateur */

        $data = array();
        $qb = $sm->finderByElementPedagogique($object);
        $modulateurs = $sm->getList($qb);
        foreach( $modulateurs as $modulateur ){
            $data[$modulateur->getTypeModulateur()->getCode()] = $modulateur->getId();
        }
        
        return $data;
    }

}