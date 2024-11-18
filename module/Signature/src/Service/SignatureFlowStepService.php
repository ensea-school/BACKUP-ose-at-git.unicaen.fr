<?php

namespace Signature\Service;


use Application\Service\AbstractEntityService;
use UnicaenSignature\Entity\Db\SignatureFlowStep;


/**
 * Description of SignatureFlowStepService
 *
 *
 * @method SignatureFlowStep get($id)
 * @method SignatureFlowStep[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method SignatureFlowStep newEntity()
 *
 */
class SignatureFlowStepService extends AbstractEntityService
{

    /**
     * Retourne la classe des entités
     *
     * @return string
     * @throws \RuntimeException
     */
    public function getEntityClass(): string
    {
        return SignatureFlowStep::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
    {
        return 'sfs';
    }



}
