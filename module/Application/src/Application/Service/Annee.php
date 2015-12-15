<?php

namespace Application\Service;

use UnicaenApp\Traits\SessionContainerTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Annee as AnneeEntity;

/**
 * Description of Annee
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Annee extends AbstractEntityService
{
    use SessionContainerTrait;

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return AnneeEntity::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'annee';
    }



    /**
     * Retourne l'année N - x.
     *
     * @param AnneeEntity $annee Année de référence
     * @param int         $x     Entier supérieur ou égal à zéro
     *
     * @return AnneeEntity
     */
    public function getNmoins(AnneeEntity $annee, $x)
    {
        return $this->get($annee->getId() - (int)$x);
    }



    /**
     *
     * @param AnneeEntity $annee
     *
     * @return AnneeEntity
     */
    public function getPrecedente(AnneeEntity $annee)
    {
        return $this->get($annee->getId() - 1);
    }



    /**
     *
     * @param AnneeEntity $annee
     *
     * @return AnneeEntity
     */
    public function getSuivante(AnneeEntity $annee)
    {
        return $this->get($annee->getId() + 1);
    }



    /**
     * Retourne la liste des ID des années sélectionnables
     */
    public function getChoixAnnees()
    {
        $session = $this->getSessionContainer();
        if (! $session->choixAnnees) {
            $sql    = 'SELECT id, libelle FROM annee WHERE active = 1 ORDER BY id';
            $stmt   = $this->getEntityManager()->getConnection()->executeQuery($sql);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $session->choixAnnees = [];
            foreach ($result as $annee) {
                extract(array_change_key_case($annee, CASE_LOWER));
                $session->choixAnnees[$id] = $libelle;
            }
        }
        return $session->choixAnnees;
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }

}