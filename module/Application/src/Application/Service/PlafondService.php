<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Plafond;

/**
 * Description of PlafondService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Plafond get($id)
 * @method Plafond[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Plafond newEntity()
 *
 */
class PlafondService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Plafond::class;
    }



    public function controle( Intervenant $intervenant )
    {
        $sqlp = file_get_contents('data/Query/plafond.sql');
        $sqlp = str_replace( '/*i.id*/', 'AND i.id = '.$intervenant->getId(), $sql);

        $sql = "
        SELECT 
          * 
        FROM 
          ($sqlp) t 
          JOIN plafond p ON p.id = t.plafond_id 
          JOIN type_volume_horaire tvh ON tvh.id = t.type_volume_horaire_id
        ";

        $res = $this->getEntityManager()->getConnection()->fetchAll($sql);
var_dump($res);
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'plafond';
    }

}