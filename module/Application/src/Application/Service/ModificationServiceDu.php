<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;


/**
 * Description of ModificationServiceDu
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDu extends AbstractEntityService
{
    use Traits\IntervenantAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return 'Application\Entity\Db\ModificationServiceDu';
    }

    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(){
        return 'msd';
    }

    /**
     * Filtre la liste selon le contexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null $alias
     * @return QueryBuilder
     */
    public function finderByContext( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);

        $this->join( $this->getServiceIntervenant(), $qb, 'intervenant', false, $alias );
        $this->getServiceIntervenant()->finderByAnnee( $this->getServiceContext()->getannee(), $qb );

        $role = $this->getServiceContext()->getSelectedIdentityRole();
        if ($intervenant = $role->getIntervenant()){
            $this->finderByIntervenant($intervenant);
        }

        return $qb;
    }

    /**
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string $alias
     */
    public function getTotal( QueryBuilder $qb=null, $alias=null )
    {
        list($qb,$alias) = $this->initQuery($qb, $alias);
        $list = $this->getList($qb);
        $total = 0;
        foreach( $list as $modif ){
            $total += $modif->heures;
        }
        return $total;
    }
}