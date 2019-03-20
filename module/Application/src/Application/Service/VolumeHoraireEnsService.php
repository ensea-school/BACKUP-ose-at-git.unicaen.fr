<?php

namespace Application\Service;

use Application\Entity\Db\VolumeHoraireEns;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Entity\Db\ElementPedagogique;
use Application\Entity\Db\TypeIntervention;


/**
 * Description of VolumeHoraireEnsService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method VolumeHoraireEns get($id)
 * @method VolumeHoraireEns[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 */
class VolumeHoraireEnsService extends AbstractEntityService
{
    use SourceServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return VolumeHoraireEns::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'vhe';
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return \Application\Entity\Db\VolumeHoraireEns
     */
    public function newEntity(ElementPedagogique $elementPedagogique = null, TypeIntervention $typeIntervention = null)
    {
        /** @var VolumeHoraireEns $entity */
        $entity = parent::newEntity();

        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());

        if ($elementPedagogique) {
            $entity->setElementPedagogique($elementPedagogique);
        }

        if ($typeIntervention) {
            $entity->setTypeIntervention($typeIntervention);
        }

        return $entity;
    }



    public function changeHeuresGroupes(VolumeHoraireEns $volumeHoraireEns, $heures, $groupes)
    {
        $changed  = false;
        $toDelete = false;

        if ($volumeHoraireEns->getHeures() !== $heures) {
            if ($heures !== null) {
                $volumeHoraireEns->setHeures($heures);
            } else {
                $toDelete = true;
            }
            $changed = true;
        }

        if ($volumeHoraireEns->getGroupes() !== $groupes) {
            $volumeHoraireEns->setGroupes($groupes);
            $changed = true;
        }

        if ($changed) {
            if ($toDelete) {
                $this->delete($volumeHoraireEns);
            } else {
                $this->save($volumeHoraireEns);
            }
        }

        return $this;
    }
}