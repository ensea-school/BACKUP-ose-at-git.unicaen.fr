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
use Import\Processus\Import;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;


/**
 * Description of Intervenant
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class Intervenant extends AbstractEntityService
{
    use StatutIntervenantAwareTrait;
    use ImportProcessusAwareTrait;




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
     *
     * @param string      $sourceCode
     * @param AnneeEntity $annee
     *
     * @return IntervenantEntity
     */
    public function getBySourceCode($sourceCode, AnneeEntity $annee = null, $autoImport=true)
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

            $ip->execMaj( 'INTERVENANT', 'SOURCE_CODE', $sourceCode, $ip::A_INSERT );
            $id = $this->getServiceQueryGenerator()->getIdFromSourceCode( 'INTERVENANT', $sourceCode, $annee->getId() );
            if (! empty($id)){
                $ip->execMaj( 'ADRESSE_INTERVENANT', 'INTERVENANT_ID', $id, $ip::A_ALL );
                $ip->execMaj( 'AFFECTATION_RECHERCHE', 'INTERVENANT_ID', $id, $ip::A_ALL );
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
     * Retourne la liste des intervenants
     *
     * @param QueryBuilder|null $queryBuilder
     * @param string|null       $alias
     *
     * @return \Application\Entity\Db\Intervenant[]
     */
    public function getList(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.nomUsuel, $alias.prenom");

        return parent::getList($qb, $alias);
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
        if ($entity->getMontantIndemniteFc() > $plafondHcRemuFc){
            throw new \RuntimeException(
                'Le montant annuel de la rémunération FC D714-60 dépasse le plafond autorisé qui est de '
                .StringFromFloat::run($plafondHcRemuFc).' €.'
            );
        }
        return parent::save($entity);
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
}
