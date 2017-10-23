<?php

namespace Application\Service;

use UnicaenApp\Traits\SessionContainerTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Annee as AnneeEntity;

/**
 * Description of Annee
 *
 * @method AnneeEntity get($id)
 * @method AnneeEntity[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method AnneeEntity newEntity()
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
        $role = $this->getServiceContext()->getSelectedIdentityRole();
        $rid = $role ? $role->getRoleId() : '__no___role__999az';
        if (!$role || !$session->choixAnnees || !isset($session->choixAnnees[$rid])) {
            if ($role && ($intervenant = $role->getIntervenant())){
                $sql    = 'SELECT a.id, a.libelle FROM annee a JOIN intervenant i ON i.annee_id = a.id AND i.code = :code WHERE active = 1 ORDER BY id';
                $params = ['code' => $intervenant->getCode()];
            }else{
                $sql    = 'SELECT id, libelle FROM annee WHERE active = 1 ORDER BY id';
                $params = [];
            }

            $stmt   = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
            $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            if (!$session->choixAnnees) {
                $session->choixAnnees = [];
            }
            $session->choixAnnees[$rid] = [];
            foreach ($result as $annee) {
                extract(array_change_key_case($annee, CASE_LOWER));
                $session->choixAnnees[$rid][$id] = $libelle;
            }
        }
        return $session->choixAnnees[$rid];
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