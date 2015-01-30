<?php

namespace Application\Form\ServiceReferentiel;

use Zend\Stdlib\Hydrator\HydratorInterface;
use Application\Service\ContextProviderAwareInterface;
use Application\Service\ContextProviderAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwaretrait;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class SaisieFieldsetHydrator implements HydratorInterface, ContextProviderAwareInterface, EntityManagerAwareInterface
{
    use ContextProviderAwareTrait;
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
        $object->setIntervenant($intervenant ? $em->getRepository('Application\Entity\Db\Intervenant')->findOneBySourceCode($intervenant) : null );

        $structure = isset($data['structure']) ? (int) $data['structure'] : null;
        $object->setStructure($structure ? $em->find('Application\Entity\Db\Structure', $structure) : null );
        
        $fonction = isset($data['fonction']) ? (int) $data['fonction'] : null;
        $object->setFonction($fonction ? $em->find('Application\Entity\Db\FonctionReferentiel', $fonction) : null );
        
        $heures = isset($data['heures']) ? (float) $data['heures'] : 0;
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
        $data = array();

        if ($object) {
            $data['id'] = $object->getId();
        }
        
        if ($object->getIntervenant()) {
            $data['intervenant'] = array(
                'id'    => $object->getIntervenant()->getSourceCode(),
                'label' => (string) $object->getIntervenant()
            );
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

        $data['heures'] = $object->getVolumeHoraireReferentielListe()->getHeures();

        $data['commentaires'] = $object->getCommentaires();
        
        return $data;
    }
}