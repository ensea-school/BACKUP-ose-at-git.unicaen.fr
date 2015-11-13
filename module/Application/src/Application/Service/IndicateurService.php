<?php

namespace Application\Service;

use Application\Service\Traits\IntervenantAwareTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Utilisateur as UtilisateurEntity;
use Application\Entity\Db\Indicateur as IndicateurEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Service\Indicateur\AbstractIndicateurImpl;


/**
 * Description of IndicateurService
 *
 */
class IndicateurService extends AbstractEntityService
{
    use IntervenantAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass()
    {
        return IndicateurEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'indic';
    }



    /**
     * @param integer|IndicateurEntity $indicateur Indicateur concerné
     *
     * @return int
     */
    private function getIndicateurNumero($indicateur)
    {
        if ($indicateur instanceof IndicateurEntity) {
            return (integer)$indicateur->getNumero();
        } else {
            return (integer)$indicateur;
        }
    }



    /**
     * @param integer|IndicateurEntity $indicateur Indicateur concerné
     * @param null                     $structure
     *
     * @return QueryBuilder
     */
    private function getBaseQueryBuilder($indicateur, $structure = null)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->from('Application\Entity\Db\Indicateur\Indicateur' . $this->getIndicateurNumero($indicateur), 'indicateur');

        /* Filtrage par intervenant */
        $qb->join('indicateur.intervenant', 'intervenant');
        $this->getServiceIntervenant()->finderByHistorique($qb, 'intervenant');
        $this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getAnnee(), $qb, 'intervenant');

        /* Filtreage par structure, si nécessaire */
        if ($structure) {
            $qb->andWhere('indicateur.structure = ' . $structure->getId());
        }

        return $qb;
    }



    private function getQueryBuilder($indicateur, StructureEntity $structure = null)
    {
        $qb = $this->getBaseQueryBuilder($indicateur, $structure);

        $qb->addSelect('indicateur');
        $qb->addSelect('intervenant');

        /* Pour l'optimisation!! */
        $methodName = 'appendQueryBuilder' . $this->getIndicateurNumero($indicateur);
        if (method_exists($this, $methodName)) {
            $this->$methodName($qb);
        }

        return $qb;
    }



    private function appendQueryBuilder210(QueryBuilder $qb)
    {
        $qb->addSelect('structure');
        $qb->join('indicateur.structure', 'structure');

        $qb->addSelect('typeAgrement');
        $qb->join('indicateur.typeAgrement', 'typeAgrement');

        $this->getServiceIntervenant()->orderBy($qb, 'intervenant');
    }



    /**
     * @param integer|IndicateurEntity $indicateur Indicateur concerné
     * @param StructureEntity|null     $structure
     */
    public function getCount($indicateur, StructureEntity $structure = null)
    {
        /* COMPATIBILITE ANCIEN SYSTEME */
        if (! $indicateur instanceof IndicateurEntity){
            $indic = $this->getByNumero($indicateur);
            $numero = $indicateur;
        }else{
            $indic = $indicateur;
            $numero = $indicateur->getNumero();
        }
        if (! class_exists('Application\Entity\Db\Indicateur\Indicateur'.$numero)){
            return $this->getIndicateurImpl($indic,$structure)->getResultCount();
        }
        /* FIN COMPATIBILITE ANCIEN SYSTEME */

        $qb = $this->getBaseQueryBuilder($indicateur, $structure);
        $qb->addSelect('COUNT(intervenant) result');

        return (integer)$qb->getQuery()->getResult()[0]['result'];
    }



    /**
     * @param integer|IndicateurEntity $indicateur Indicateur concerné
     *
     * @return IndicateurEntity\AbstractIndicateur[]
     */
    public function getResult($indicateur, StructureEntity $structure = null)
    {
        $qb = $this->getQueryBuilder($indicateur, $structure);


        $entities = $qb->getQuery()->execute();
        $result = [];
//        $entityClass = $this->getEntityClass();
        foreach ($entities as $entity) {
//            if ($entity instanceof $entityClass) {
                $result[$entity->getId()] = $entity;
//            }
        }

        return $result;
    }



    /**
     *
     * @param integer $numero
     *
     * @return \Application\Entity\Db\Indicateur
     */
    public function getByNumero($numero)
    {
        if (null == $numero) return null;

        $indicateur = $this->getRepo()->findOneBy(['numero' => $numero]);
        $indicateur->setServiceIndicateur($this);
        return $indicateur;
    }



    /**
     *
     * @param string $code
     *
     * @return \Application\Entity\Db\Indicateur
     */
    public function getByCode($code)
    {
        if (null == $code) return null;

        $indicateur = $this->getRepo()->findOneBy(['code' => $code]);
        $indicateur->setServiceIndicateur($this);
        return $indicateur;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.type, $alias.ordre");

        return $qb;
    }



    /**
     *
     * @param QueryBuilder $qb
     * @param string|null  $alias
     *
     * @return \Application\Entity\Db\Indicateur[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->andWhere("$alias.enabled = 1");

        $list = parent::getList($qb, $alias);
        /* @var $list IndicateurEntity[] */

        foreach( $list as $indicateur ){
            $indicateur->setServiceIndicateur($this);
        }

        return $list;
    }



    /**
     *
     * @param IndicateurEntity $indicateur
     * @param StructureEntity  $structure
     *
     * @return AbstractIndicateurImpl
     */
    public function getIndicateurImpl(IndicateurEntity $indicateur, StructureEntity $structure = null)
    {
        /** @var AbstractIndicateurImpl $impl */
        $impl = clone $this->getServiceLocator()->get($indicateur->getCode());
        $impl
            ->setIndicateurEntity($indicateur)
            ->setStructure($structure);

        return $impl;
    }



    /**
     *
     * @param IndicateurEntity[] $indicateurs
     * @param StructureEntity    $structure
     *
     * @return AbstractIndicateurImpl[]
     */
    public function getIndicateursImpl($indicateurs, StructureEntity $structure = null)
    {
        $impls = [];
        foreach ($indicateurs as $indicateur) {
            $impls[$indicateur->getId()] = $this->getIndicateurImpl($indicateur, $structure);
        }

        return $impls;
    }



    /**
     * Suppression (historisation) de l'historique des modifications sur les données perso d'un intervenant.
     *
     * @param IntervenantEntity $intervenant
     * @param UtilisateurEntity $destructeur
     *
     * @return $this
     */
    public function purgerIndicateurDonneesPersoModif(IntervenantEntity $intervenant, UtilisateurEntity $destructeur)
    {
        $role = $this->getServiceContext()->getSelectedIdentityRole();

        $qb = $this->getEntityManager()->createQueryBuilder()
            ->update('Application\Entity\Db\IndicModifDossier', 't')
            ->set("t.histoDestruction", ":destruction")
            ->set("t.histoDestructeur", ":destructeur")
            ->where("t.intervenant = :intervenant")
            ->andWhere("1 = pasHistorise(t)");

        $qb
            ->setParameter('intervenant', $intervenant)
            ->setParameter('destructeur', $destructeur)
            ->setParameter('destruction', new \DateTime());

        $qb->getQuery()->execute();

        return $this;
    }
}