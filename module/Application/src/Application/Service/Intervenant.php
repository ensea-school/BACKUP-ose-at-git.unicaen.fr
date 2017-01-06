<?php

namespace Application\Service;

use Application\Entity\Db\Intervenant as IntervenantEntity;
use Application\Entity\Db\Structure as StructureEntity;
use Application\Entity\Db\Periode as PeriodeEntity;
use Application\Entity\Db\Annee as AnneeEntity;
use Application\Entity\Db\TypeIntervenant;
use Application\Filter\StringFromFloat;
use Application\Service\Traits\StatutIntervenantAwareTrait;
use RuntimeException;
use Doctrine\ORM\QueryBuilder;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;
use UnicaenImport\Service\Traits\QueryGeneratorServiceAwareTrait;


/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends AbstractEntityService
{
    use StatutIntervenantAwareTrait;
    use ImportProcessusAwareTrait;
    use QueryGeneratorServiceAwareTrait;



    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return IntervenantEntity::class;
    }



    /**
     * Retourne l'intervenant de l'année précédente
     *
     * @param IntervenantEntity $intervenant
     *
     * @return IntervenantEntity
     */
    public function getPrecedent(IntervenantEntity $intervenant)
    {
        return $this->getBySourceCode(
            $intervenant->getSourceCode(),
            $this->getServiceContext()->getAnneePrecedente(),
            false
        );
    }



    /**
     *
     * @param string      $sourceCode
     * @param AnneeEntity $annee
     *
     * @return IntervenantEntity
     */
    public function getBySourceCode($sourceCode, AnneeEntity $annee = null, $autoImport = true)
    {
        if (null == $sourceCode) return null;

        if (!$annee) {
            $annee = $this->getServiceContext()->getAnnee();
        }

        $findParams = ['sourceCode' => (string)$sourceCode, 'annee' => $annee->getId()];
        $repo       = $this->getRepo();

        $result = $repo->findOneBy($findParams);
        if (!$result && $autoImport) {
            $ip = $this->getProcessusImport();

            $ip->execMaj('INTERVENANT', 'SOURCE_CODE', $sourceCode, $ip::A_INSERT);
            $id = $this->getServiceQueryGenerator()->getIdFromSourceCode('INTERVENANT', $sourceCode, $annee->getId());
            if (!empty($id)) {
                $ip->execMaj('ADRESSE_INTERVENANT', 'INTERVENANT_ID', $id, $ip::A_ALL);
                $ip->execMaj('AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $id, $ip::A_ALL);
            }

            $result = $repo->findOneBy($findParams); // on retente
        }

        return $result;
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



    public function finderByMiseEnPaiement(StructureEntity $structure = null, PeriodeEntity $periode = null, QueryBuilder $qb = null, $alias = null)
    {
        $serviceMIS = $this->getServiceLocator()->get('applicationMiseEnPaiementIntervenantStructure');
        /* @var $serviceMIS MiseEnPaiementIntervenantStructure */

        $serviceMiseEnPaiement = $this->getServiceLocator()->get('applicationMiseEnPaiement');
        /* @var $serviceMiseEnPaiement MiseEnPaiement */

        list($qb, $alias) = $this->initQuery($qb, $alias);

        $this->join($serviceMIS, $qb, 'miseEnPaiementIntervenantStructure', false, $alias);
        $serviceMIS->join($serviceMiseEnPaiement, $qb, 'miseEnPaiement');

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
     * @param IntervenantEntity $entity
     *
     * @throws \RuntimeException
     * @return IntervenantEntity
     */
    public function save($entity)
    {
        $plafondHcRemuFc = $entity->getStatut()->getPlafondHcRemuFc();
        if ($entity->getMontantIndemniteFc() > $plafondHcRemuFc) {
            throw new \RuntimeException(
                'Le montant annuel de la rémunération FC D714-60 dépasse le plafond autorisé qui est de '
                . StringFromFloat::run($plafondHcRemuFc) . ' €.'
            );
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
     * @param IntervenantEntity $entity Entité à détruire
     * @param bool  $softDelete
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
                  AND 0 = OSE_DIVERS.COMPRISE_ENTRE(s.histo_creation, s.histo_destruction) 
                )
            ";

            $sqls[] = "
            DELETE FROM volume_horaire_ref vh
              WHERE 
                service_referentiel_id IN (SELECT id FROM service_referentiel s WHERE 
                  intervenant_id = $id
                  AND 0 = OSE_DIVERS.COMPRISE_ENTRE(s.histo_creation, s.histo_destruction) 
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
                  AND 0 = OSE_DIVERS.COMPRISE_ENTRE(histo_creation, histo_destruction)";
            }

            foreach( $sqls as $sql ){
                $this->getEntityManager()->getConnection()->executeQuery($sql);
            }
        }

        return parent::delete($entity, $softDelete); // TODO: Change the autogenerated stub
    }

}
