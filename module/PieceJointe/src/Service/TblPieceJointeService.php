<?php

namespace PieceJointe\Service;

use Application\Entity\Db\Fichier;
use Application\Provider\Privilege\Privileges;
use Application\Service\AbstractEntityService;
use Intervenant\Entity\Db\Intervenant;
use Mission\Assertion\SaisieAssertion;
use Mission\Entity\Db\Mission;
use PieceJointe\Entity\Db\TblPieceJointe;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Entity\Db\Validation;

/**
 * Description of TblPieceJointeService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method TblPieceJointe get($id)
 * @method TblPieceJointe[] getList(\Doctrine\ORM\QueryBuilder $qb = null, $alias = null)
 * @method TblPieceJointe newEntity()
 *
 */
class TblPieceJointeService extends AbstractEntityService
{

    /**
     * retourne la classe des entités
     *
     * @return string
     * @throws RuntimeException
     */
    public function getEntityClass()
    {
        return TblPieceJointe::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias()
    {
        return 'tblpj';
    }



    public function data(Intervenant $intervenant): AxiosModel
    {
        $dql = "
        SELECT 
          tpj, t, pj, pjv, f, uv, uf
        FROM 
          " . TblPieceJointe::class . " tpj
           LEFT JOIN tpj.typePieceJointe t 
           LEFT JOIN tpj.pieceJointe pj
           LEFT JOIN pj.validation pjv
           LEFT JOIN pjv.histoCreateur uv
           LEFT JOIN pj.fichier f
           LEFT JOIn f.histoCreateur uf
           LEFT JOIN f.validation vf
        WHERE 
           tpj.intervenant =  :intervenant
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameter('intervenant', $intervenant);


        $properties = [
            'id',
            'demandee',
            'fournie',
            'validee',
            ['typePieceJointe', ['libelle']],
            ['pieceJointe',
             [
                 ['validation', ['id', 'histoCreation']],
                 ['fichier',
                  ['nom',
                   ['validation', ['id']],
                  ]],
             ],
            ],
        ];

        $triggers = [
            '/pieceJointe/validation'         => function (?Validation $validation, $extracted) {
                if (!empty($validation)) {
                    $extracted['utilisateur'] = $validation->getHistoCreateur()->getDisplayName();
                    $extracted['date']        = $validation->getHistoCreation()->format('d-m-Y H:i:s');
                }
                return $extracted;
            },
            '/pieceJointe/fichier'            => function (?Fichier $fichier, $extracted) {
                if (!empty($fichier)) {
                    $extracted['poids']       = round($fichier->getTaille() / 1024) . ' Ko';
                    $extracted['type']        = $fichier->getTypeMime();
                    $extracted['date']        = $fichier->getDate()->format('d-m-Y H:i:s');
                    $extracted['utilisateur'] = $fichier->getHistoCreateur()->getDisplayName();
                }
                return $extracted;
            },
            '/pieceJointe/fichier/validation' => function (?Validation $validation, $extracted) {
                if (!empty($validation)) {
                    $extracted['utilisateur'] = $validation->getHistoCreateur()->getDisplayName();
                    $extracted['date']        = $validation->getHistoCreation()->format('d-m-Y H:i:s');

                }
                return $extracted;
            },
        ];


        return new AxiosModel($query, $properties, $triggers);
    }

}