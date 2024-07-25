<?php

namespace Signature\Service;


use Application\Service\AbstractEntityService;
use UnicaenSignature\Entity\Db\SignatureFlow;


/**
 * Description of SignatureFlowService
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 *
 * @method SignatureFlow get($id)
 * @method SignatureFlow[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method SignatureFlow newEntity()
 *
 */
class SignatureFlowService extends AbstractEntityService
{

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return SignatureFlow::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'sf';
    }

}
