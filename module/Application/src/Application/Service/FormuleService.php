<?php

namespace Application\Service;

use Application\Entity\Db\Formule;

/**
 * Description of FormuleService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Formule get($id)
 * @method Formule[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Formule newEntity()
 *
 */
class FormuleService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Formule::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'formule';
    }

}