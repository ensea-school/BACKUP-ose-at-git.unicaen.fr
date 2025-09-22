<?php

namespace Referentiel\Service;

use Application\Service\AbstractEntityService;
use Referentiel\Entity\Db\FonctionReferentiel;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of FonctionReferentielService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method FonctionReferentiel get($id)
 * @method FonctionReferentiel[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 */
class FonctionReferentielService extends AbstractEntityService
{
    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return FonctionReferentiel::class;
    }



    public function newEntity()
    {
        /** @var FonctionReferentiel $entity */
        $entity = parent::newEntity();
        $entity->setStructure($this->getServiceContext()->getStructure());

        return $entity;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'fonc_ref';
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder|mixed|null
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.libelleLong");

        return $qb;
    }



    /**
     * @param string $code
     * @param string $annee
     *
     * @return FonctionReferentiel
     */
    public function getFonctionByCodeAndAnnee(string $code, ?string $annee)
    {
        if($annee == null){
            $annee = $this->getServiceContext()->getAnnee();
        }
        $result = $this->getRepo()
            ->findOneBy(['code' => $code, 'annee' => $annee, 'histoDestruction' => null]);

        return $result;

    }


}