<?php

namespace Indicateur\Service;

use Application\Cache\Traits\CacheContainerTrait;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Structure;
use Application\Service\AbstractService;
use DateTime;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Entity\Db\NotificationIndicateur;
use phpDocumentor\Reflection\Types\Array_;
use Plafond\Service\IndicateurServiceAwareTrait as PlafondIndicateurServiceAwareTrait;


/**
 * Description of IndicateurService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method Indicateur get($id)
 * @method Indicateur newEntity()
 *
 */
class IndicateurService extends AbstractService
{
    use CacheContainerTrait;
    use PlafondIndicateurServiceAwareTrait;


    protected function getViewDef(int $numero, Annee $annee): string
    {
        $view    = 'V_INDICATEUR_' . $numero;
        $sql     = "SELECT text FROM user_views WHERE view_name = :view";
        $viewDef = $this->getEntityManager()->getConnection()->fetchAssociative($sql, compact('view'))['TEXT'];

        return $viewDef;
    }



    protected function fetchData(Indicateur $indicateur, ?Structure $structure = null, bool $onlyCount = true): array
    {
        $numero    = $indicateur->getNumero();
        $structure = $structure ?: $this->getServiceContext()->getStructure();
        $annee     = $this->getServiceContext()->getAnnee();

        if ($indicateur->getTypeIndicateur()->isPlafond()) {
            $viewDef = $this->getServiceIndicateur()->makeQuery($indicateur);
        } else {
            $viewDef = $this->getViewDef($numero, $annee);
        }

        $params = [
            'annee' => $annee->getId(),
        ];
        if ($onlyCount) {
            $select  = "COUNT(DISTINCT i.id) NB";
            $orderBy = "";
        } else {
            $select  = "
            i.annee_id                 \"annee-id\",
            si.libelle                 \"statut-libelle\",
            si.prioritaire_indicateurs \"prioritaire\",
            i.code_rh                  \"intervenant-code-rh\",
            i.code                     \"intervenant-code\",
            i.prenom                   \"intervenant-prenom\",
            i.nom_usuel                \"intervenant-nom\",
            i.email_perso              \"intervenant-email-perso\",
            i.email_pro                \"intervenant-email-pro\",
            s.libelle_court            \"structure-libelle\",
            indic.*";
            $orderBy = " ORDER BY si.prioritaire_indicateurs DESC, s.libelle_court, i.nom_usuel, i.prenom";
        }

        $sql = "SELECT
          $select
        FROM
          ($viewDef) indic
          JOIN intervenant    i ON i.id = indic.intervenant_id AND i.histo_destruction IS NULL
          JOIN statut        si ON si.id = i.statut_id AND si.code <> 'NON_AUTORISE'
          LEFT JOIN structure s ON s.id = indic.structure_id
        WHERE
          i.annee_id = :annee
        ";
        if (!$indicateur->isIrrecevables()) {
            $sql .= ' AND i.irrecevable = 0';
        }
        if ($structure) {
            $params['structure'] = $structure->getId();
            $sql                 .= ' AND (indic.structure_id = :structure OR indic.structure_id IS NULL)';
        }
        $sql .= $orderBy;

        return $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
    }



    /**
     * @param integer|Indicateur $indicateur Indicateur concerné
     */
    public function getCount(Indicateur $indicateur)
    {
        $data = $this->fetchData($indicateur, null, true);

        return (integer)$data[0]['NB'];
    }



    /**
     * @param Indicateur $indicateur Indicateur concerné
     *
     * @return array
     */
    public function getResult(NotificationIndicateur|Indicateur $indicateur): array
    {
        if ($indicateur instanceof NotificationIndicateur) {
            $structure  = $indicateur->getAffectation()->getStructure();
            $indicateur = $indicateur->getIndicateur();
        } else {
            $structure = null;
        }
        $data   = $this->fetchData($indicateur, $structure, false);
        $result = [];

        foreach ($data as $d) {
            $id = (int)$d['INTERVENANT_ID'];
            // on initialise les données communes à tous les indicateurs
            if (!isset($result[$id])) {
                $result[$id] = [
                    'annee-id'                => (int)$d['annee-id'],
                    'statut-libelle'          => $d['statut-libelle'],
                    'prioritaire'             => (bool)$d['prioritaire'],
                    'intervenant-code'        => $d['intervenant-code'],
                    'intervenant-prenom'      => $d['intervenant-prenom'],
                    'intervenant-nom'         => $d['intervenant-nom'],
                    'intervenant-email-pro'   => $d['intervenant-email-pro'],
                    'intervenant-email-perso' => $d['intervenant-email-perso'],
                ];
            }

            // on n'en a plus besoin pour la suite
            unset($d['annee-id']);
            unset($d['statut-libelle']);
            unset($d['prioritaire']);
            unset($d['intervenant-code']);
            unset($d['intervenant-prenom']);
            unset($d['intervenant-nom']);
            unset($d['intervenant-email-pro']);
            unset($d['intervenant-email-perso']);
            unset($d['INTERVENANT_ID']);
            unset($d['STRUCTURE_ID']);

            // on injecte les données supplémentaires s'il y en a
            foreach ($d as $field => $value) {
                if (array_key_exists($field, $result[$id])) {
                    if (is_array($result[$id][$field])) {
                        if (!in_array($value, $result[$id][$field])) {
                            $result[$id][$field][] = $value;
                        }
                    } else {
                        if ($result[$id][$field] !== $value) {
                            $result[$id][$field] = [$result[$id][$field], $value];
                        }
                    }
                } else {
                    $result[$id][$field] = $value;
                }
            }
        }

        return $result;
    }



    public function getCsv(Indicateur $indicateur): array
    {
        $data   = $this->fetchData($indicateur, null, false);
        $result = [];

        foreach ($data as $d) {
            unset($d['INTERVENANT_ID']);
            unset($d['STRUCTURE_ID']);
            $d['annee-id']    = $d['annee-id'] . '/' . ((int)$d['annee-id'] + 1);
            $d['prioritaire'] = $d['prioritaire'] ? 'Oui' : 'Non';
            $count            = -1;
            $datePresentes    = [];


            //Regarde si les colonnes sont dans le format date pour l'afficher, si elles le sont ajoute le nom de la colonne dans l'array
            foreach ($d as $dateTest) {
                $count++;
                if (!is_array($dateTest)) {
                    $dt = DateTime::createFromFormat('Y-m-d H:i:s', $dateTest);
                    if ($dt && $dt->format('Y-m-d H:i:s') === $dateTest) {
                        $keys            = array_keys($d);
                        $datePresentes[] = $keys[$count];
                    }
                }
            }

            //Formate les date trouvé lors du parcours précedent au format voulu
            foreach ($datePresentes as $datePresente) {
                $dt               = DateTime::createFromFormat('Y-m-d H:i:s', $d[$datePresente]);
                $d[$datePresente] = $dt->format(\Application\Constants::DATE_FORMAT);
            }
            $result[] = $d;
        }

        return $result;
    }

}