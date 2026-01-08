<?php

namespace OffreFormation\Service;

use Application\Service\AbstractEntityService;
use Application\Service\Traits\SourceServiceAwareTrait;
use OffreFormation\Entity\Db\CheminPedagogique;
use OffreFormation\Entity\Db\ElementPedagogique;

/**
 * Description of CheminPedagogique
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class CheminPedagogiqueService extends AbstractEntityService
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
        return \OffreFormation\Entity\Db\CheminPedagogique::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'cp';
    }



    /**
     * Retourne une nouvelle entité, initialisée avec les bons paramètres
     *
     * @return Etape
     */
    public function newEntity()
    {
        $entity = parent::newEntity();
        // toutes les entités créées ont OSE pour source!!
        $entity->setSource($this->getServiceSource()->getOse());
        $entity->setOrdre(1);

        return $entity;
    }



    /**
     * @param CheminPedagogique $entity
     *
     * @return CheminPedagogique
     */
    public function save($entity)
    {
        if (!$entity->getSource()) {
            $entity->setSource($this->getServiceSource()->getOse());
        }
        if (!$entity->getSourceCode()) {
            $prefix = 'EP' . $entity->getEtape()->getId() . $entity->getElementPedagogique()->getId();
            $entity->setSourceCode(uniqid($prefix));
        }

        return parent::save($entity);
    }



    /**
     * Vérifie si le chemin pédagogique existe pour l'element pédagogique donné
     *
     * @param ElementPedagogique $entity
     *
     * @return bool
     */
    public function exist($entity): bool
    {
        return (bool)$this->entityManager
            ->getRepository(CheminPedagogique::class)
            ->findOneBy([
                            'etape'              => $entity->getEtape(),
                            'elementPedagogique' => $entity,
                            'histoDestruction'   => null,
                        ]);
    }



    /**
     * Remplace le chemin pédagogique pour un element pédagogique non importé
     *
     * @param ElementPedagogique $entity
     *
     * @return void
     */
    public function replaceCheminsPedagogiques($entity): void
    {
        foreach ($entity->getCheminPedagogique() as $chemin) {
            $this->remove($chemin);
        }

        $this->create($entity);
    }



    /**
     * Historise un chemin pédagogique
     *
     * @param CheminPedagogique $entity
     *
     * @return void
     */
    public function remove(CheminPedagogique $chemin): void
    {
        $chemin->getEtape()->removeCheminPedagogique($chemin);
        $chemin->getElementPedagogique()->removeCheminPedagogique($chemin);

        if ($chemin->estNonHistorise()) {
            $this->delete($chemin);
        }
    }



    /**
     * Créer un chemin pédagogique
     *
     * @param ElementPedagogique $entity
     *
     * @return void
     */

    public function create($entity): void
    {
        $chemin = $this->newEntity();
        $chemin->setEtape($entity->getEtape())
               ->setElementPedagogique($entity);

        $entity->addCheminPedagogique($chemin);
        $entity->getEtape()->addCheminPedagogique($chemin);

        $this->save($chemin);

    }
}