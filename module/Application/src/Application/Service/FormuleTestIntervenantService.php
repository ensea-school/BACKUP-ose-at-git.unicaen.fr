<?php

namespace Application\Service;

use Application\Entity\Db\FormuleTestIntervenant;

/**
 * Description of FormuleTestIntervenantService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method FormuleTestIntervenant get($id)
 * @method FormuleTestIntervenant[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method FormuleTestIntervenant newEntity()
 *
 */
class FormuleTestIntervenantService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return FormuleTestIntervenant::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fti';
    }



    /**
     * @param FormuleTestIntervenant $formuleTestIntervenant
     *
     * @return FormuleTestIntervenantService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculer(FormuleTestIntervenant $formuleTestIntervenant): FormuleTestIntervenantService
    {
        $sql = "BEGIN ose_formule.test(" . ((int)$formuleTestIntervenant->getId()) . "); END;";
        $this->getEntityManager()->getConnection()->exec($sql);

        $this->getEntityManager()->refresh($formuleTestIntervenant);
        foreach( $formuleTestIntervenant->getVolumeHoraireTest() as $vhe ){
            $this->getEntityManager()->refresh($vhe);
        }

        return $this;
    }



    /**
     * @return FormuleTestIntervenantService
     * @throws \Doctrine\DBAL\DBALException
     */
    public function calculerTout(): FormuleTestIntervenantService
    {
        $sql = "BEGIN ose_formule.test_tout; END;";
        $this->getEntityManager()->getConnection()->exec($sql);

        return $this;
    }



    /**
     * Sauvegarde une entité
     *
     * @param FormuleTestIntervenant $entity
     *
     * @throws \RuntimeException
     * @return mixed
     */
    public function save($entity)
    {
        parent::save($entity);

        foreach ($entity->getVolumeHoraireTest() as $vhe) {
            $this->getEntityManager()->persist($vhe);
            $this->getEntityManager()->flush($vhe);
        }

        return $entity;
    }

}