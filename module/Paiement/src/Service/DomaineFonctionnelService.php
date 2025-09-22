<?php

namespace Paiement\Service;

use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Paiement\Entity\Db\DomaineFonctionnel;
use RuntimeException;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class DomaineFonctionnelService extends AbstractEntityService
{
    use \Administration\Service\ParametresServiceAwareTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return DomaineFonctionnel::class;
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'df';
    }

    /**
     * Retourne le domaine fonctionnel par défaut pour les services assurés à l'extérieur défini selon les paramètres OSE
     *
     * @return DomaineFonctionnel
     */
    public function getForServiceExterieur()
    {
        $dfId = $this->getServiceParametres()->get('domaine_fonctionnel_ens_ext');
        return $this->get($dfId);
    }

    /**
     * Retourne la liste des étapes
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return \Paiement\Entity\Db\DomaineFonctionnel[]
     */
    public function getList(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.libelle");

        return parent::getList($qb, $alias);
    }
}