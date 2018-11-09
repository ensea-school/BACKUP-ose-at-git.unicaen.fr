<?php

namespace Application\Service;

use Doctrine\ORM\QueryBuilder;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\Annee;
use Application\Entity\Db\Structure;


/**
 * Description of AgrementService
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class AgrementService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return Agrement::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'a';
    }



    /**
     * Sauvegarde une entité
     *
     * @param Agrement $entity
     *
     * @throws \RuntimeException
     * @return mixed
     */
    public function save($entity)
    {
        if (!$this->getAuthorize()->isAllowed($entity, $entity->getType()->getPrivilegeEdition())) {
            $errorMsg = 'Vous n\'avez pas le droit de saisir l\'agrément de ' . $entity->getIntervenant();
            if ($structure = $entity->getStructure()) {
                $errorMsg .= ', ' . $structure;
            }
            throw new \RuntimeException($errorMsg);
        }

        return parent::save($entity); // TODO: Change the autogenerated stub
    }



    /**
     * @param QueryBuilder|null $qb
     * @param null              $alias
     *
     * @return QueryBuilder
     */
    public function orderBy(QueryBuilder $qb = null, $alias = null)
    {
        list($qb, $alias) = $this->initQuery($qb, $alias);
        $qb->addOrderBy("$alias.id");

        return $qb;
    }



    public function getExportCsvData(Annee $annee, Structure $structure = null)
    {
        $params = ['annee' => $annee->getId()];

        $sql = "SELECT * FROM V_AGREMENT_EXPORT_CSV WHERE annee_id = :annee";
        if ($structure) {
            $sql                 .= " AND structure_id = :structure";
            $params['structure'] = $structure->getId();
        }

        $data = $this->getEntityManager()->getConnection()->fetchAll($sql, $params);
        $res  = [
            'head' => [
                'annee'                        => 'Année',
                'structure-libelle'            => 'Structure d\'affectation',
                'intervenant-code'             => 'Code intervenant',
                'intervenant-nom-usuel'        => 'Nom usuel',
                'intervenant-nom-patronymique' => 'Nom patronymique',
                'intervenant-prenom'           => 'Prénom',
                'intervenant-statut-libelle'   => 'Statut',
                'premier-recrutement'          => 'Premier recrutement',
                'discipline'                   => 'Discipline',
                'hetd-fi'                      => 'HETD (FI)',
                'hetd-fa'                      => 'HETD (FA)',
                'hetd-fc'                      => 'HETD (FC)',
                'hetd-total'                   => 'HETD (Total)',
                'type-agrement'                => 'Type d\'agrément',
                'agree'                        => 'Agréé',
                'date-decision'                => 'Date de décision',
                'modificateur'                 => 'Modificateur',
                'date-modification'            => 'Date de modification',
            ],
            'data' => [],
        ];
        foreach ($data as $d) {
            $res['data'][] = [
                'annee'                        => $d['ANNEE'],
                'structure-libelle'            => $d['STRUCTURE_LIBELLE'],
                'intervenant-code'             => $d['INTERVENANT_CODE'],
                'intervenant-nom-usuel'        => $d['INTERVENANT_NOM_USUEL'],
                'intervenant-nom-patronymique' => $d['INTERVENANT_NOM_PATRONYMIQUE'],
                'intervenant-prenom'           => $d['INTERVENANT_PRENOM'],
                'intervenant-statut-libelle'   => $d['INTERVENANT_STATUT_LIBELLE'],
                'premier-recrutement'          => $d['PREMIER_RECRUTEMENT'] == '1' ? 'Oui' : 'Non',
                'discipline'                   => $d['DISCIPLINE'],
                'hetd-fi'                      => (float)$d['HETD_FI'],
                'hetd-fa'                      => (float)$d['HETD_FA'],
                'hetd-fc'                      => (float)$d['HETD_FC'],
                'hetd-total'                   => (float)$d['HETD_TOTAL'],
                'type-agrement'                => $d['TYPE_AGREMENT'],
                'agree'                        => $d['AGREE'] == '1' ? 'Oui' : 'En attente',
                'date-decision'                => $d['DATE_DECISION'] ? new \DateTime($d['DATE_DECISION']) : null,
                'modificateur'                 => $d['MODIFICATEUR'],
                'date-modification'            => $d['DATE_MODIFICATION'] ? new \DateTime($d['DATE_MODIFICATION']) : null,
            ];
        }

        return $res;
    }

}