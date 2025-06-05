<?php

namespace Paiement\Service;


use Application\Service\AbstractService;
use Lieu\Entity\Db\Structure;

/**
 * Description of BudgetService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BudgetService extends AbstractService
{
    use DotationServiceAwareTrait;
    use TypeRessourceServiceAwareTrait;


    /**
     * Retourne les données du TBL des services en fonction des critères de recherche transmis
     *
     * @param Recherche $recherche
     *
     * @return array
     */
    public function getTableauBord(?Structure $structure)
    {
        $annee = $this->getServiceContext()->getAnnee();
        $data  = [];

        $params = [
            'annee' => $annee->getId(),
        ];
        $sql    = 'SELECT * FROM v_export_dmep WHERE annee_id = :annee';

        if ($structure) {
            $params['structure'] = $structure->idsFilter();
            $sql                 .= ' AND structure_ids LIKE :structure';
        }

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        // récupération des données
        while ($d = $stmt->fetch()) {

            $ds = [
                'annee-libelle' => (string)$annee,

                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-code-rh'            => $d['CODE_RH'],
                'intervenant-nom'                => $d['INTERVENANT_NOM'],
                'intervenant-date-naissance'     => new \DateTime($d['INTERVENANT_DATE_NAISSANCE']),
                'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-type-code'          => $d['INTERVENANT_TYPE_CODE'],
                'intervenant-type-libelle'       => $d['INTERVENANT_TYPE_LIBELLE'],
                'intervenant-grade-code'         => $d['INTERVENANT_GRADE_CODE'],
                'intervenant-grade-libelle'      => $d['INTERVENANT_GRADE_LIBELLE'],
                'intervenant-discipline-code'    => $d['INTERVENANT_DISCIPLINE_CODE'],
                'intervenant-discipline-libelle' => $d['INTERVENANT_DISCIPLINE_LIBELLE'],
                'service-structure-aff-libelle'  => $d['SERVICE_STRUCTURE_AFF_LIBELLE'],

                'service-structure-ens-libelle' => $d['SERVICE_STRUCTURE_ENS_LIBELLE'],
                'groupe-type-formation-libelle' => $d['GROUPE_TYPE_FORMATION_LIBELLE'],
                'type-formation-libelle'        => $d['TYPE_FORMATION_LIBELLE'],
                'etape-niveau'                  => empty($d['ETAPE_NIVEAU']) ? null : (int)$d['ETAPE_NIVEAU'],
                'etape-code'                    => $d['ETAPE_CODE'],
                'etape-etablissement-libelle'   => $d['ETAPE_LIBELLE'] ? $d['ETAPE_LIBELLE'] : $d['ETABLISSEMENT_LIBELLE'],
                'element-code'                  => $d['ELEMENT_CODE'],
                'element-fonction-libelle'      => $d['ELEMENT_LIBELLE'] ? $d['ELEMENT_LIBELLE'] : $d['FONCTION_REFERENTIEL_LIBELLE'],
                'element-discipline-code'       => $d['ELEMENT_DISCIPLINE_CODE'],
                'element-discipline-libelle'    => $d['ELEMENT_DISCIPLINE_LIBELLE'],
                'element-taux-fi'               => (float)$d['ELEMENT_TAUX_FI'],
                'element-taux-fc'               => (float)$d['ELEMENT_TAUX_FC'],
                'element-taux-fa'               => (float)$d['ELEMENT_TAUX_FA'],
                'commentaires'                  => $d['COMMENTAIRES'],
                'element-source-libelle'        => $d['ELEMENT_SOURCE_LIBELLE'],

                'type-ressource-libelle'      => $d['TYPE_RESSOURCE_LIBELLE'],
                'centre-couts-code'           => $d['CENTRE_COUTS_CODE'],
                'centre-couts-libelle'        => $d['CENTRE_COUTS_LIBELLE'],
                'domaine-fonctionnel-code'    => $d['DOMAINE_FONCTIONNEL_CODE'],
                'domaine-fonctionnel-libelle' => $d['DOMAINE_FONCTIONNEL_LIBELLE'],
                'etat'                        => $d['ETAT'],
                'periode-libelle'             => $d['PERIODE_LIBELLE'],
                'date-mise-en-paiement'       => $d['DATE_MISE_EN_PAIEMENT'] ? new \DateTime($d['DATE_MISE_EN_PAIEMENT']) : null,
                'heures-fi'                   => (float)$d['HEURES_FI'],
                'heures-fa'                   => (float)$d['HEURES_FA'],
                'heures-fc'                   => (float)$d['HEURES_FC'],
                'heures-referentiel'          => (float)$d['HEURES_REFERENTIEL'],
                'heures-primes'               => (float)$d['HEURES_PRIMES'],
            ];

            $data[] = $ds;
        }

        return $data;
    }



    public function getBudgetPaiement(Structure $structure): array
    {
        $budget = [
            'dotation'    => [
                'total'           => 0,
            ],
            'consommation' => [
                'total'           => 0,
            ],

        ];
        $dotation = $this->getServiceDotation()->getTableauBord([$structure->getId()]);
        $liquidation = $this->getTblLiquidation($structure);

        if (!empty($dotation)) {
            $dotation    = $this->getServiceDotation()->getTableauBord([$structure->getId()]);
            $liquidation = $this->getTblLiquidation($structure);
            foreach ($dotation as $key => $values) {
                if ($key == $structure->getId()) {
                    foreach ($values as $k => $v) {
                        if ($k != 'total') {
                            $typeRessources                                                = $this->getServiceTypeRessource()->get($k);
                            $budget['dotation'][$typeRessources->getCode()]['heures']      = $v;
                            $budget['dotation'][$typeRessources->getCode()]['libelle']     = $typeRessources->getLibelle();
                            $budget['consommation'][$typeRessources->getCode()]['heures']  = (key_exists($k, $liquidation)) ? $liquidation[$k] : 0;
                            $budget['consommation'][$typeRessources->getCode()]['libelle'] = $typeRessources->getLibelle();
                        } else {
                            $budget['dotation']['total'] = $values['total'];
                        }
                    }
                    break;
                }
            }
            $budget['consommation']['total'] = $liquidation['total'];

        }

        return $budget;
    }



    /**
     * Retourne le tableau de bord des liquidations.
     * Il retourne le nb d'heures demandées en paiement par type de ressource pour une structure donnée
     * et pour l'année courante
     *
     * Format de retour : [Structure.id][TypeRessource.id] = (float)Heures
     *                 ou [TypeRessource.id] = (float)Heures
     *
     * Si la structure n'est pas spécifiée alors on retourne le tableau pour chaque structure.
     *
     * @param Structure|null $structure
     *
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTblLiquidation(?Structure $structure = null): array
    {
        if (empty($structure)) return $this->getTblLiquidationMS();
        if (is_array($structure)) return $this->getTblLiquidationMS($structure);

        if (!$structure instanceof Structure) {
            throw new \RuntimeException('La structure fournie n\'est pas uns entité');
        }

        $annee = $this->getServiceContext()->getAnnee();

        $res = ['total' => 0];

        $sql = "
        SELECT
          tdl.type_ressource_id,
          tdl.heures
        FROM
          v_tbl_dmep_liquidation tdl
          JOIN structure str ON str.id = tdl.structure_id
        WHERE
          tdl.annee_id = :annee
          AND str.ids LIKE :structure";

        $params = [
            'annee'     => $annee->getId(),
            'structure' => $structure->idsFilter(),
        ];
        $stmt   = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        while ($d = $stmt->fetch()) {
            $typeRessourceId = (int)$d['TYPE_RESSOURCE_ID'];
            $heures          = (float)$d['HEURES'];

            $res[$typeRessourceId] = $heures;
            $res['total']          += $heures;
        }

        return $res;
    }



    /**
     * @param array|Structure[] $structures
     *
     * @return array|int[]
     * @throws \Doctrine\DBAL\Exception
     */
    private function getTblLiquidationMS(array $structures = [])
    {
        $annee = $this->getServiceContext()->getAnnee();

        $res = ['total' => 0];

        $sql = "
        SELECT
          structure_id,
          type_ressource_id,
          heures
        FROM
          V_TBL_DMEP_LIQUIDATION
        WHERE
          annee_id = :annee
        ";

        $strFilters = [];
        foreach ($structures as $structure) {
            $strFilters[] = 'structure_ids LIKE \'' . $structure->idsFilter() . "'";
        }
        if (!empty($strFilters)) {
            $sql .= 'AND (' . implode(' OR ', $strFilters) . ')';
        }

        $params = [
            'annee' => $annee->getId(),
        ];

        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        while ($d = $stmt->fetchAssociative()) {
            $structureId     = (int)$d['STRUCTURE_ID'];
            $typeRessourceId = (int)$d['TYPE_RESSOURCE_ID'];
            $heures          = (float)$d['HEURES'];

            $res[$structureId][$typeRessourceId] = $heures;
            if (!isset($res[$structureId]['total'])) $res[$structureId]['total'] = 0;
            $res[$structureId]['total'] += $heures;
            $res['total']               += $heures;
        }

        return $res;
    }



    /**
     * Retourne le volume d'heures prévisionnelles faites pour une structure donnée, en année universitaire (par défaut)
     * ou bien par année civile en appliquant la règle des 4/10 / 6/10.
     */
    public function getTotalPrevisionnelValide(?Structure $structure = null): array|float
    {
        if (!$structure) return $this->getTotalPrevisionnelValideWS(); // on ByPasse!!!

        $params = [
            'structure' => (integer)$structure->getId(),
            'annee'     => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql = 'SELECT HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE STRUCTURE_ID = :structure AND ANNEE_ID = :annee';
        $sr  = $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAssociative();

        if (isset($sr['HEURES'])) {
            return (float)$sr['HEURES'];
        } else {
            return (float)0;
        }
    }



    private function getTotalPrevisionnelValideWS(): array
    {
        $params = [
            'annee' => (integer)$this->getServiceContext()->getAnnee()->getId(),
        ];

        $sql  = 'SELECT STRUCTURE_ID, HEURES FROM V_HETD_PREV_VAL_STRUCT WHERE ANNEE_ID = :annee';
        $stmt = $this->getEntityManager()->getConnection()->executeQuery($sql, $params);

        $res = ['total' => 0];
        while ($d = $stmt->fetch()) {
            $structureId = (int)$d['STRUCTURE_ID'];
            $heures      = (float)$d['HEURES'];

            $res[$structureId] = $heures;
            $res['total']      += $heures;
            $res['total']      += $heures;
        }

        return $res;
    }
}