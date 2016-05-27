<?php

namespace Application\Form\ServiceReferentiel;

use Application\Filter\FloatFromString;
use Application\Filter\StringFromFloat;
use Zend\Stdlib\Hydrator\HydratorInterface;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetHydrator implements HydratorInterface, EntityManagerAwareInterface
{
    use EntityManagerAwaretrait;

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  \Application\Entity\Db\ServiceReferentiel $object
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        $em = $this->getEntityManager();

        $intervenant = isset($data['intervenant']['id']) ? (int) $data['intervenant']['id'] : null;
        $object->setIntervenant($intervenant ? $em->getRepository(\Application\Entity\Db\Intervenant::class)->findOneBySourceCode($intervenant) : null );

        $structure = isset($data['structure']) ? (int) $data['structure'] : null;
        $object->setStructure($structure ? $em->find(\Application\Entity\Db\Structure::class, $structure) : null );

        $fonction = isset($data['fonction']) ? (int) $data['fonction'] : null;
        $object->setFonction($fonction ? $em->find(\Application\Entity\Db\FonctionReferentiel::class, $fonction) : null );

        $heures = isset($data['heures']) ? FloatFromString::run($data['heures']) : 0;
        $object->getVolumeHoraireReferentielListe()->setHeures($heures);

        $commentaires = isset($data['commentaires']) ? $data['commentaires'] : null;
        $object->setCommentaires($commentaires);

        return $object;
    }

    /**
     * Extract values from an object
     *
     * @param  \Application\Entity\Db\ServiceReferentiel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];

        if ($object) {
            $data['id'] = $object->getId();
        }

        if ($object->getIntervenant()) {
            $data['intervenant'] = [
                'id'    => $object->getIntervenant()->getRouteParam(),
                'label' => (string) $object->getIntervenant()
            ];
        }
        else {
            $data['intervenant'] = null;
        }

        if ($object->getStructure()) {
            $data['structure'] = $object->getStructure()->getId();
        }
        else {
            $data['structure'] = null;
        }

        if ($object->getFonction()) {
            $data['fonction'] = $object->getFonction()->getId();
        }
        else {
            $data['fonction'] = null;
        }

        $data['heures'] = StringFromFloat::run($object->getVolumeHoraireReferentielListe()->getHeures());

        $data['commentaires'] = $object->getCommentaires();

        return $data;
    }
}