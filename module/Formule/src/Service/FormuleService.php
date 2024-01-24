<?php

namespace Formule\Service;

use Application\Service\AbstractEntityService;
use Application\Service\RuntimeException;
use Application\Service\Traits\ParametresServiceAwareTrait;
use Formule\Entity\Db\Formule;


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
    use ParametresServiceAwareTrait;


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
    public function getAlias()
    {
        return 'formule';
    }



    /**
     * @return Formule
     */
    public function getCurrent(): Formule
    {
        $formuleId = $this->getServiceParametres()->get('formule');

        return $this->get($formuleId);
    }

}