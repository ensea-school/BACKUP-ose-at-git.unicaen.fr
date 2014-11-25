<?php
namespace Application\Entity\Service;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheHydrator implements HydratorInterface, ServiceLocatorAwareInterface
{

    use ServiceLocatorAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        /* @var $object Recherche */

        $sTypeIntervenant = $this->getServiceLocator()->get('applicationTypeIntervenant');
        /* @var $sTypeIntervenant \Application\Service\TypeIntervenant */

        $sIntervenant = $this->getServiceLocator()->get('applicationIntervenant');
        /* @var $sIntervenant \Application\Service\Intervenant */

        $sStructure = $this->getServiceLocator()->get('applicationStructure');
        /* @var $sStructure \Application\Service\Structure */

        $sNiveauEtape = $this->getServiceLocator()->get('applicationNiveauEtape');
        /* @var $sNiveauEtape \Application\Service\NiveauEtape */

        $sEtape = $this->getServiceLocator()->get('applicationEtape');
        /* @var $sEtape \Application\Service\Etape */

        $sElementPedagogique = $this->getServiceLocator()->get('applicationElementPedagogique');
        /* @var $sElementPedagogique \Application\Service\ElementPedagogique */

        $sTypeVolumeHoraire = $this->getServiceLocator()->get('applicationTypeVolumeHoraire');
        /* @var $sTypeVolumeHoraire \Application\Service\TypeVolumeHoraire */

        $sEtatVolumeHoraire = $this->getServiceLocator()->get('applicationEtatVolumeHoraire');
        /* @var $sEtatVolumeHoraire \Application\Service\EtatVolumeHoraire */


        $id = isset($data['type-intervenant']) ? (int)$data['type-intervenant'] : null;
        $object->setTypeIntervenant( $sTypeIntervenant->get( $id ) );

        $id = isset($data['structure-aff']) ? (int)$data['structure-aff'] : null;
        $object->setStructureAff( $sStructure->get( $id ) );

        $id = isset($data['intervenant']) ? $data['intervenant'] : null;
        $object->setIntervenant( $sIntervenant->get( $id ) );

        $id = isset($data['structure-ens']) ? (int)$data['structure-ens'] : null;
        $object->setStructureEns( $sStructure->get( $id ) );

        $id = isset($data['niveau-etape']) ? $data['niveau-etape'] : null;
        $object->setNiveauEtape( $sNiveauEtape->get( $id ) );

        $id = isset($data['etape']) ? (int)$data['etape'] : null;
        $object->setEtape( $sEtape->get( $id ) );

        $id = isset($data['element-pedagogique']) ? (int)$data['element-pedagogique'] : null;
        $object->setElementPedagogique( $sElementPedagogique->get( $id ) );

        $id = isset($data['type-volume-horaire']) ? (int)$data['type-volume-horaire'] : null;
        $object->setTypeVolumeHoraire( $sTypeVolumeHoraire->get( $id ) );

        $id = isset($data['etat-volume-horaire']) ? (int)$data['etat-volume-horaire'] : null;
        $object->setEtatVolumeHoraire( $sEtatVolumeHoraire->get( $id ) );

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\ElementPedagogique $object
     * @return array
     */
    public function extract($object)
    {
        /* @var $object Recherche */

        $data = [
            'type-intervenant'      => $object->getTypeIntervenant()    ? $object->getTypeIntervenant()->getId()    : null,
            'structure-aff'         => $object->getStructureAff()       ? $object->getStructureAff()->getId()       : null,
            'intervenant'           => $object->getIntervenant()        ? $object->getIntervenant()->getId()        : null,
            'structure-ens'         => $object->getStructureEns()       ? $object->getStructureEns()->getId()       : null,
            'niveau-etape'          => $object->getNiveauEtape()        ? $object->getNiveauEtape()->getId()        : null,
            'etape'                 => $object->getEtape()              ? $object->getEtape()->getId()              : null,
            'element-pedagogique'   => $object->getElementPedagogique() ? $object->getElementPedagogique()->getId() : null,
            'type-volume-horaire'   => $object->getTypeVolumeHoraire()  ? $object->getTypeVolumeHoraire()->getId()  : null,
            'etat-volume-horaire'   => $object->getEtatVolumeHoraire()  ? $object->getEtatVolumeHoraire()->getId()  : null,
        ];
        return $data;
    }

}