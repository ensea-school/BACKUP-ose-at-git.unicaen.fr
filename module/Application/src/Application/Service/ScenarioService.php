<?php

namespace Application\Service;

use Application\Assertion\ChargensAssertion;
use Application\Connecteur\Bdd\BddConnecteur;
use Application\Connecteur\Bdd\BddConnecteurAwareTrait;
use Application\Entity\Db\Scenario;
use Application\Service\Traits\ContextServiceAwareTrait;
use BjyAuthorize\Exception\UnAuthorizedException;
use Doctrine\ORM\QueryBuilder;

/**
 * Description of ScenarioService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Scenario get($id)
 * @method Scenario[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 *
 */
class ScenarioService extends AbstractEntityService
{
    use ContextServiceAwareTrait;
    use BddConnecteurAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Scenario::class;
    }



    /**
     * Filtre la liste des services selon lecontexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        if ($structure = $this->getServiceContext()->getStructure()) {
            $qb->andWhere($alias . '.structure = :structure OR ' . $alias . '.structure IS NULL')->setParameter(
                'structure', $structure
            );
        }

        return $qb;
    }



    /**
     * @param Scenario $source
     * @param Scenario $destination
     *
     * @return $this
     */
    public function dupliquer(Scenario $source, Scenario $destination, $noeuds = '', $liens = '')
    {
        $bdd = new BddConnecteur();
        $bdd->setEntityManager($this->getEntityManager());

        $bdd->execPlsql('OSE_CHARGENS.DUPLIQUER(:source, :destination, :utilisateur, :structure, :noeuds, :liens);', [
            'source'      => $source->getId(),
            'destination' => $destination->getId(),
            'utilisateur' => $this->getServiceContext()->getUtilisateur()->getId(),
            'structure'   => $this->getServiceContext()->getStructure() ? $this->getServiceContext()->getStructure()->getId() : null,
            'noeuds'      => $noeuds,
            'liens'       => $liens,
        ]);

        return $this;
    }



    /**
     * Retourne une nouvelle entité de la classe donnée
     *
     * @return mixed
     */
    public function newEntity()
    {
        $class = parent::newEntity();
        if ($structure = $this->getServiceContext()->getStructure()) {
            $class->setStructure($structure);
        }

        return $class;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Scenario $entity Entité à détruire
     * @param bool     $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        $conn = $this->getEntityManager()->getConnection();

        $uid = (string)(int)$this->getServiceContext()->getUtilisateur()->getId();
        $sid = $entity->getId();

        $sql = "
          UPDATE 
            %s 
          SET 
            HISTO_DESTRUCTION = SYSDATE, HISTO_DESTRUCTEUR_ID = $uid 
          WHERE 
            scenario_id = $sid AND HISTO_DESTRUCTION IS NULL
        ";

        $conn->exec(sprintf($sql, 'scenario_noeud'));
        $conn->exec(sprintf($sql, 'scenario_lien'));

        return parent::delete($entity, $softDelete);
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'scn';
    }

}