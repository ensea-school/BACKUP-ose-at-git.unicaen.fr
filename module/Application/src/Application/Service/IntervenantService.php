<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Structure;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Annee;
use Application\Entity\Db\TypeIntervenant;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\MiseEnPaiementServiceAwareTrait;
use Application\Service\Traits\MiseEnPaiementIntervenantStructureServiceAwareTrait;
use Application\Service\Traits\SourceServiceAwareTrait;
use Application\Service\Traits\StatutIntervenantServiceAwareTrait;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;


/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 *
 * @method Intervenant get($id)
 * @method Intervenant[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method Intervenant newEntity()
 */
class IntervenantService extends AbstractEntityService
{
    use StatutIntervenantServiceAwareTrait;
    use ImportProcessusAwareTrait;
    use MiseEnPaiementServiceAwareTrait;
    use MiseEnPaiementIntervenantStructureServiceAwareTrait;
    use WorkflowServiceAwareTrait;
    use SourceServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Intervenant::class;
    }



    /**
     * Retourne l'intervenant de l'année précédente
     *
     * @param Intervenant $intervenant
     *
     * @return Intervenant
     */
    public function getPrecedent(Intervenant $intervenant)
    {
        return $this->getBySourceCode(
            $intervenant->getSourceCode(),
            $this->getServiceContext()->getAnneePrecedente(),
            false
        );
    }



    /**
     * Retourne les identifiants des données concernés
     *
     * @param string|string[]|null $sourceCode
     * @param integer|null         $anneeId
     *
     * @return integer[]|null
     */
    protected function getId($column, $value, $anneeId = null)
    {
        if (empty($sourceCode)) return null;

        $sql = 'SELECT ID FROM INTERVENANT WHERE '.$column.' IN (:value)';
        if ($anneeId) {
            $sql .= ' AND ANNEE_ID = ' . (string)(int)$anneeId;
        }
        $stmt = $this->getEntityManager()->getConnection()->executeQuery(
            $sql,
            ['value' => (array)$value],
            ['value' => \Doctrine\DBAL\Connection::PARAM_INT_ARRAY]
        );
        if ($r = $stmt->fetch()) {
            return (int)$r['ID'];
        } else {
            return null;
        }
    }



    /**
     *
     * @param string $sourceCode
     * @param Annee  $annee
     *
     * @return Intervenant
     */
    public function getBy($attribute, $column, $value, Annee $annee = null, $autoImport = true)
    {
        if (null == $value) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        $findParams = [$attribute => (string)$value, 'annee' => $annee->getId()];
        $repo       = $this->getRepo();

        $result = $repo->findOneBy($findParams);
        if (!$result && $autoImport) {
            $ip = $this->getProcessusImport();

            $ip->execMaj('INTERVENANT', $column, $value, $ip::A_INSERT);
            $id = $this->getId($column, $value, $annee->getId());
            if (!empty($id)) {
                $ip->execMaj('ADRESSE_INTERVENANT', 'INTERVENANT_ID', $id, $ip::A_ALL);
                $ip->execMaj('AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $id, $ip::A_ALL);
            }

            $result = $repo->findOneBy($findParams); // on retente
            if ($result) {
                $this->getServiceWorkflow()->calculerTableauxBord(null, $result);
            }
        }

        return $result;
    }



    /**
     *
     * @param string $sourceCode
     * @param Annee  $annee
     *
     * @return Intervenant|null
     */
    public function getBySourceCode($sourceCode, Annee $annee = null, $autoImport = true)
    {
        return $this->getBy('sourceCode', 'SOURCE_CODE', $sourceCode, $annee, $autoImport);
    }



    /**
     *
     * @param string $sourceCode
     * @param Annee  $annee
     *
     * @return Intervenant|null
     */
    public function getByUtilisateurCode($utilisateurCode, Annee $annee = null, $autoImport = true)
    {
        return $this->getBy('utilisateurCode', 'UTILISATEUR_CODE', $utilisateurCode, $annee, $autoImport);
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'int';
    }



    public function finderByMiseEnPaiement(Structure $structure = null, Periode $periode = null, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceMiseEnPaiementIntervenantStructure();

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($this->getServiceMiseEnPaiement(), $qb, 'miseEnPaiement');

        if ($structure) {
            $serviceMIS->finderByStructure($structure, $qb);
        }
        if ($periode) {
            $serviceMIS->finderByPeriode($periode, $qb);
        }

        return $qb;
    }



    /**
     * Sauvegarde une entité
     *
     * @param Intervenant $entity
     *
     * @throws \RuntimeException
     * @return Intervenant
     */
    public function save($entity)
    {
        if (!$entity->getSource()){
            $entity->setSource($this->getServiceSource()->getOse());
        }

        return parent::save($entity);
    }



    /**
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);

        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");

        return $qb;
    }



    /**
     * Filtre par le type d'intervenant
     *
     * @param TypeIntervenant   $typeIntervenant Type de l'intervenant
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByType(TypeIntervenant $typeIntervenant, QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $sStatut = $this->getServiceStatutIntervenant();

        $this->join($sStatut, $qb, 'statut', false, $alias);
        $sStatut->finderByTypeIntervenant($typeIntervenant, $qb);

        return $qb;
    }



    /**
     * Supprime (historise par défaut) le service spécifié.
     *
     * @param Intervenant $entity Entité à détruire
     * @param bool        $softDelete
     *
     * @return self
     */
    public function delete($entity, $softDelete = true)
    {
        if (!$softDelete) {
            $id   = (int)$entity->getId();
            $sqls = [];

            $sqls[] = "
            DELETE FROM volume_horaire vh
              WHERE 
                service_id IN (SELECT id FROM service s WHERE 
                  intervenant_id = $id
                  AND s.histo_destruction IS NOT NULL 
                )
            ";

            $sqls[] = "
            DELETE FROM volume_horaire_ref vh
              WHERE 
                service_referentiel_id IN (SELECT id FROM service_referentiel s WHERE 
                  intervenant_id = $id
                  AND s.histo_destruction IS NOT NULL 
                )
            ";

            $depTables = [
                'INDIC_MODIF_DOSSIER',
                'AGREMENT',
                'CONTRAT',
                'DOSSIER',
                'MODIFICATION_SERVICE_DU',
                'PIECE_JOINTE',
                'SERVICE_REFERENTIEL',
                'ADRESSE_INTERVENANT',
                'AFFECTATION_RECHERCHE',
                'SERVICE',
                'VALIDATION',
            ];

            foreach ($depTables as $depTable) {
                $sqls[] = "
                DELETE FROM
                  $depTable
                WHERE
                  intervenant_id = $id
                  AND histo_destruction IS NOT NULL";
            }

            foreach ($sqls as $sql) {
                $this->getEntityManager()->getConnection()->executeQuery($sql);
            }
        }

        return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
    }



    /**
     * @param string      $nom
     * @param string      $prenom
     * @param \DateTime   $dateNaissance
     * @param string|null $statut
     *
     * @return Intervenant
     */
    public function creerIntervenant(string $nom, string $prenom, \DateTime $dateNaissance, string $statut=null): Intervenant
    {
        $code = uniqid('OSE');

        $intervenant = new Intervenant;
        $intervenant->setAnnee($this->getServiceContext()->getAnnee());
        $intervenant->setCode($code);

        $intervenant->setSource($this->getServiceSource()->getOse());
        $intervenant->setSourceCode($code);

        $intervenant->setNomUsuel($nom);
        $intervenant->setPrenom($prenom);
        $intervenant->setDateNaissance($dateNaissance);
        $intervenant->setStatut($this->getServiceStatutIntervenant()->getByCode($statut));

        $this->save($intervenant);

        return $intervenant;
    }

}
