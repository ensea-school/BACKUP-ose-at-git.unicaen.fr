<?php

namespace Service\Hydrator;

use Application\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Laminas\Hydrator\HydratorInterface;
use Lieu\Entity\Db\Structure;
use OffreFormation\Entity\Db\ElementPedagogique;
use OffreFormation\Entity\Db\Etape;
use OffreFormation\Service\Traits\NiveauEtapeServiceAwareTrait;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;
use Service\Entity\Recherche;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;


/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RechercheHydrator implements EntityManagerAwareInterface, HydratorInterface
{
    use EntityManagerAwareTrait;
    use NiveauEtapeServiceAwareTrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param array     $data
     * @param Recherche $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $em = $this->getEntityManager();

        $id = isset($data['type-intervenant']) ? (int)$data['type-intervenant'] : null;
        $object->setTypeIntervenant($id ? $em->find(TypeIntervenant::class, $id) : null);

        $id = isset($data['structure-aff']) ? (int)$data['structure-aff'] : null;
        $object->setStructureAff($id ? $em->find(Structure::class, $id) : null);

        $id = isset($data['intervenant']) ? $data['intervenant'] : null;
        $object->setIntervenant($id ? $em->find(Intervenant::class, $id) : null);

        $id = isset($data['structure-ens']) ? (int)$data['structure-ens'] : null;
        $object->setStructureEns($id ? $em->find(Structure::class, $id) : null);

        $id = isset($data['niveau-etape']) ? $data['niveau-etape'] : null;
        $object->setNiveauEtape($this->getServiceNiveauEtape()->get($id));

        $id = isset($data['etape']) ? (int)$data['etape'] : null;
        $object->setEtape($id ? $em->find(Etape::class, $id) : null);

        $id = isset($data['element-pedagogique']) ? (int)$data['element-pedagogique'] : null;
        $object->setElementPedagogique($id ? $em->find(ElementPedagogique::class, $id) : null);

        $id = isset($data['type-volume-horaire']) ? (int)$data['type-volume-horaire'] : null;
        $object->setTypeVolumeHoraire($id ? $em->find(TypeVolumeHoraire::class, $id) : null);

        $id = isset($data['etat-volume-horaire']) ? (int)$data['etat-volume-horaire'] : null;
        $object->setEtatVolumeHoraire($id ? $em->find(EtatVolumeHoraire::class, $id) : null);

        return $object;
    }



    /**
     * Extract values from an object
     *
     * @param \OffreFormation\Entity\Db\ElementPedagogique $object
     *
     * @return array
     */
    public function extract($object): array
    {
        /* @var $object Recherche */

        $data = [
            'type-intervenant'    => $object->getTypeIntervenant() ? $object->getTypeIntervenant()->getId() : null,
            'structure-aff'       => $object->getStructureAff() ? $object->getStructureAff()->getId() : null,
            'intervenant'         => $object->getIntervenant() ? $object->getIntervenant()->getId() : null,
            'structure-ens'       => $object->getStructureEns() ? $object->getStructureEns()->getId() : null,
            'niveau-etape'        => $object->getNiveauEtape() ? $object->getNiveauEtape()->getId() : null,
            'etape'               => $object->getEtape() ? $object->getEtape()->getId() : null,
            'element-pedagogique' => $object->getElementPedagogique() ? $object->getElementPedagogique()->getId() : null,
            'type-volume-horaire' => $object->getTypeVolumeHoraire() ? $object->getTypeVolumeHoraire()->getId() : null,
            'etat-volume-horaire' => $object->getEtatVolumeHoraire() ? $object->getEtatVolumeHoraire()->getId() : null,
        ];

        return $data;
    }

}