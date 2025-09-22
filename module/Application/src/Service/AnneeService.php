<?php

namespace Application\Service;

use UnicaenApp\Traits\SessionContainerTrait;
use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Annee;

/**
 * Description of AnneeService
 *
 * @method Annee[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Annee newEntity()
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AnneeService extends AbstractEntityService
{
    use SessionContainerTrait;

    /** @var Annee[] */
    private array $cache = [];


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Annee::class;
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



    public function get($id, $autoClear = false)
    {
        if (!array_key_exists($id, $this->cache) || $autoClear) {
            $this->cache[$id] = parent::get($id, $autoClear);
        }
        return $this->cache[$id];
    }



    /**
     * Retourne l'année N - x.
     *
     * @param Annee $annee Année de référence
     * @param int   $x     Entier supérieur ou égal à zéro
     *
     * @return Annee
     */
    public function getNmoins(Annee $annee, int $x): Annee
    {
        return $this->get($annee->getId() - $x);
    }



    public function getPrecedente(Annee $annee): Annee
    {
        return $this->get($annee->getId() - 1);
    }



    public function getSuivante(Annee $annee): Annee
    {
        return $this->get($annee->getId() + 1);
    }



    public function resetChoixAnnees(): self
    {
        $session              = $this->getSessionContainer();
        $session->choixAnnees = null;

        return $this;
    }



    /**
     * @return array|Annee[]
     */
    public function getActives(bool $desc = false): array
    {
        $dql = "SELECT a FROM ".Annee::class." a WHERE a.active = 1 ORDER BY a.id";
        if ($desc){
            $dql .= " DESC";
        }

        $query = $this->getEntityManager()->createQuery($dql);

        return $query->getResult();
    }



    /**
     * Retourne la liste des ID des années sélectionnables
     */
    public function getChoixAnnees(): array
    {
        $session = $this->getSessionContainer();
        $role    = $this->getServiceContext()->getSelectedIdentityRole();
        $rid     = $role ? $role->getRoleId() : '__no___role__999az';
        if (!$role || !$session->choixAnnees || !isset($session->choixAnnees[$rid])) {
            if ($role && ($intervenant = $role->getIntervenant())) {
                $sql    = 'SELECT a.id, a.libelle 
                          FROM annee a
                          JOIN parametre p ON p.nom = \'annee\'
                          LEFT JOIN intervenant i ON i.annee_id = a.id AND i.code = :code
                          WHERE active = 1 AND (i.id IS NOT NULL OR a.id = p.valeur)
                          ORDER BY id';
                $params = ['code' => $intervenant->getCode()];
            } else {
                $sql    = 'SELECT id, libelle FROM annee WHERE active = 1 ORDER BY id';
                $params = [];
            }

            $result = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);

            if (!$session->choixAnnees) {
                $session->choixAnnees = [];
            }
            $session->choixAnnees = [$rid => []];
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
     */
    public function orderBy(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }

}
