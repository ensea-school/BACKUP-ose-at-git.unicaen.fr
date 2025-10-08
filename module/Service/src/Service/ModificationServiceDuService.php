<?php

namespace Service\Service;

use Application\Entity\Db\Annee;
use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Service\IntervenantServiceAwareTrait;
use Lieu\Entity\Db\Structure;


/**
 * Description of ModificationServiceDu
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class ModificationServiceDuService extends AbstractEntityService
{
    use IntervenantServiceAwareTrait;


    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return \Service\Entity\Db\ModificationServiceDu::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'msd';
    }



    /**
     * Filtre la liste selon le contexte courant
     *
     * @param QueryBuilder|null $qb
     * @param string|null       $alias
     *
     * @return QueryBuilder
     */
    public function finderByContext(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);

        $this->join($this->getServiceIntervenant(), $qb, 'intervenant', false, $alias);
        $this->getServiceIntervenant()->finderByAnnee($this->getServiceContext()->getannee(), $qb);

        if ($intervenant = $this->getServiceContext()->getIntervenant()) {
            $this->finderByIntervenant($intervenant);
        }

        return $qb;
    }



    /**
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param string                     $alias
     */
    public function getTotal(?QueryBuilder $qb = null, $alias = null)
    {
        [$qb, $alias] = $this->initQuery($qb, $alias);
        $list  = $this->getList($qb);
        $total = 0;
        foreach ($list as $modif) {
            $total += $modif->heures;
        }

        return $total;
    }



    public function getExportCsvData(Annee $annee, Structure $structure = null)
    {
        $params = ['annee' => $annee->getId()];

        $sql = "SELECT * FROM V_MODIF_SERVICE_DU_EXPORT_CSV WHERE annee_id = :annee";
        if ($structure) {
            $sql                 .= " AND structure_ids LIKE :structure";
            $params['structure'] = $structure->idsFilter();
        }

        $data = $this->getEntityManager()->getConnection()->fetchAllAssociative($sql, $params);
        $res  = [
            'head' => [
                'annee'                          => 'Année',
                'structure-libelle'              => 'Structure d\'affectation',
                'intervenant-code'               => 'Code intervenant',
                'intervenant-nom-usuel'          => 'Nom usuel',
                'intervenant-nom-patronymique'   => 'Nom patronymique',
                'intervenant-prenom'             => 'Prénom',
                'intervenant-statut-libelle'     => 'Statut',
                'intervenant-service-statutaire' => 'Service statutaire',
                'motif-code'                     => 'Motif (code)',
                'motif-libelle'                  => 'Motif (libellé)',
                'heures'                         => 'heures',
                'commentaires'                   => 'Commentaires',
                'modificateur'                   => 'Modificateur',
                'date-modification'              => 'Date de modification',
            ],
            'data' => [],
        ];
        foreach ($data as $d) {
            $res['data'][] = [
                'annee'                          => $d['ANNEE'],
                'structure-libelle'              => $d['STRUCTURE_LIBELLE'],
                'intervenant-code'               => $d['INTERVENANT_CODE'],
                'intervenant-nom-usuel'          => $d['INTERVENANT_NOM_USUEL'],
                'intervenant-nom-patronymique'   => $d['INTERVENANT_NOM_PATRONYMIQUE'],
                'intervenant-prenom'             => $d['INTERVENANT_PRENOM'],
                'intervenant-statut-libelle'     => $d['INTERVENANT_STATUT_LIBELLE'],
                'intervenant-service-statutaire' => (float)$d['INTERVENANT_SERVICE_STATUTAIRE'],
                'motif-code'                     => $d['MOTIF_CODE'],
                'motif-libelle'                  => $d['MOTIF_LIBELLE'],
                'heures'                         => (float)$d['HEURES'],
                'commentaires'                   => $d['COMMENTAIRES'],
                'modificateur'                   => $d['MODIFICATEUR'],
                'date-modification'              => new \DateTime($d['DATE_MODIFICATION']),
            ];
        }

        return $res;
    }
}