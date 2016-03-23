<?php

namespace Application\Service;

use Application\Entity\Db\Fichier;

/**
 * Description of FichierService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Fichier get($id)
 * @method Fichier[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Fichier newEntity()
 *
 */
class FichierService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Fichier::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'fich';
    }

}