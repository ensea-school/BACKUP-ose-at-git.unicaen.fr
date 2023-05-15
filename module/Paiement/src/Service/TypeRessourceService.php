<?php

namespace Paiement\Service;
use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Paiement\Entity\Db\TypeRessource;


/**
 * Description of TypeRessourceService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method TypeRessource get($id)
 * @method TypeRessource[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method TypeRessource newEntity()
 *
 */
class TypeRessourceService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TypeRessource::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'typeress';
    }

}