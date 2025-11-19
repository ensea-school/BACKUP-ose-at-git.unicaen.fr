<?php

namespace PieceJointe\Service;

use Application\Entity\Db\Fichier;
use Application\Provider\Privileges;
use Application\Service\AbstractEntityService;
use Doctrine\ORM\QueryBuilder;
use Intervenant\Entity\Db\Intervenant;
use PieceJointe\Entity\Db\PieceJointe;
use PieceJointe\Entity\Db\TblPieceJointe;
use UnicaenVue\View\Model\AxiosModel;
use Workflow\Entity\Db\Validation;
use Workflow\Entity\Db\WfEtape;
use Workflow\Entity\Db\WorkflowEtape;
use Workflow\Service\WorkflowServiceAwareTrait;

/**
 * Description of TblPieceJointeService
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 *
 * @method TblPieceJointe get($id)
 * @method TblPieceJointe[] getList(QueryBuilder $qb = null, $alias = null)
 * @method TblPieceJointe newEntity()
 *
 */
class TblPieceJointeService extends AbstractEntityService
{
    use WorkflowServiceAwareTrait;

    /**
     * Retourne la classe des entités
     *
     * @return string
     */
    public function getEntityClass(): string
    {
        return TblPieceJointe::class;
    }



    /**
     * Retourne l'alias d'entité courante
     *
     * @return string
     */
    public function getAlias(): string
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
           LEFT JOIN f.histoCreateur uf
           LEFT JOIN f.validation vf
        WHERE 
           tpj.intervenant =  :intervenant
        ";

        $query = $this->getEntityManager()->createQuery($dql)->setParameter('intervenant', $intervenant);

        $datas                        = new \stdClass();
        $datas->piecesJointes         = $query->getResult();
        $datas->messagesPiecesJointes = $this->makeMessages($intervenant);
        $datas->privileges            = [
            'canVisualiser'     => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_VISUALISATION)),
            'canEditer'         => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_EDITION)),
            'canValider'        => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_VALIDATION)),
            'canDevalider'      => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION)),
            'canRefuser'        => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_REFUS_PIECE)),
            'canTelecharger'    => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_TELECHARGEMENT)),
            'canEditerComp'     => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_EDITION_COMP)),
            'canValiderComp'    => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_VALIDATION_COMP)),
            'canDevaliderComp'  => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_DEVALIDATION_COMP)),
            'canVisualiserComp' => $this->getAuthorize()->isAllowed(Privileges::getResourceId(Privileges::PIECE_JUSTIFICATIVE_VISUALISATION_COMP)),
        ];


        $properties = [['piecesJointes',
                        [
                            'id',
                            'demandee',
                            'fournie',
                            'validee',
                            'demandeApresRecrutement',
                            ['typePieceJointe',
                             ['id',
                              'libelle',
                              'urlModeleDoc']],
                            ['pieceJointe',
                             ['id',
                              ['validation',
                               ['id',
                                'histoCreation']],
                              ['fichier',
                               ['id',
                                'nom',
                                ['validation',
                                 ['id']],
                               ]],
                             ],
                            ],
                        ],
                       ],
                       'messagesPiecesJointes',
                       ['type',
                        'text'],
                       'privileges',
                       ['canVisualiser',
                        'canEditer',
                        'canValider',
                        'canDevalider',
                        'canTelecharger',
                        'canEditerComp',
                        'canValiderComp',
                        'canDevaliderComp',
                        'caenVisualiser',],
        ];


        $triggers = [
            '/piecesJointes'                                => function (?TblPieceJointe $tblPieceJointe, $extracted) {

                $extracted['annee'] = $tblPieceJointe->getAnnee()->getId();
                return $extracted;
            },
            '/piecesJointes/pieceJointe'                    => function (?PieceJointe $pieceJointe, $extracted) {
                if (!empty($pieceJointe)) {
                    $extracted['anneeOrigine'] = $pieceJointe->getIntervenant()->getAnnee()->getId();
                }
                return $extracted;
            },
            '/piecesJointes/pieceJointe/validation'         => function (?Validation $validation, $extracted) {
                if (!empty($validation)) {
                    $extracted['utilisateur'] = $validation->getHistoCreateur()->getDisplayName();
                    $extracted['date']        = $validation->getHistoCreation()->format('d-m-Y H:i:s');
                }
                return $extracted;
            },
            '/piecesJointes/pieceJointe/fichier'            => function (?Fichier $fichier, $extracted) {
                if (!empty($fichier)) {
                    $extracted['poids']       = round($fichier->getTaille() / 1024) . ' Ko';
                    $extracted['type']        = $fichier->getTypeMime();
                    $extracted['date']        = $fichier->getHistoCreation()->format('d-m-Y à H:i:s');
                    $extracted['utilisateur'] = $fichier->getHistoCreateur()->getDisplayName();
                }
                return $extracted;
            },
            '/piecesJointes/pieceJointe/fichier/validation' => function (?Validation $validation, $extracted) {
                if (!empty($validation)) {
                    $extracted['utilisateur'] = $validation->getHistoCreateur()->getDisplayName();
                    $extracted['date']        = $validation->getHistoCreation()->format('d-m-Y à H:i:s');

                }
                return $extracted;
            },
        ];


        return new AxiosModel($datas, $properties, $triggers);
    }



    protected function makeMessages(Intervenant $intervenant): array
    {
        $feuilleDeRoute = $this->getServiceWorkflow()->getFeuilleDeRoute($intervenant);

        $workflowEtapePjSaisie = $feuilleDeRoute->get(WorkflowEtape::PJ_SAISIE);
        $workflowEtapePjValide = $feuilleDeRoute->get(WorkflowEtape::PJ_VALIDATION);
        $msgs                  = [];

        if ($workflowEtapePjSaisie != null) {
            if (!$workflowEtapePjSaisie->isFranchie()) {
                $msg['avantRecrutement'] = 1;
                $msg['type']             = 'danger';
                $msg['text']             = "Des pièces justificatives obligatoires n'ont pas été fournies.";
                $msgs[]                  = $msg;
                unset($msg);
            } elseif ($workflowEtapePjSaisie->isFranchie() && $workflowEtapePjValide->isFranchie()) {
                $msg['avantRecrutement'] = 1;
                $msg['type']             = 'success';
                $msg['text']             = "Toutes les pièces justificatives obligatoires ont été fournies et validées.";
                $msgs[]                  = $msg;
                unset($msg);
            } elseif ($workflowEtapePjSaisie->isFranchie() && !$workflowEtapePjValide->isFranchie()) {
                $msg['avantRecrutement'] = 1;
                $msg['type']             = 'success';
                $msg['text']             = "Toutes les pièces justificatives obligatoires ont été fournies.";
                $msgs[]                  = $msg;
                unset($msg);
                $msg['avantRecrutement'] = 1;
                $msg['type']             = 'warning';
                $msg['text']             = "Mais certaines doivent encore être validées par un gestionnaire.";
                $msgs[]                  = $msg;
                unset($msg);
            }
        }

        $workflowEtapePjSaisieComplementaire      = $feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_SAISIE);
        $workflowEtapePjValideApresComplementaire = $feuilleDeRoute->get(WorkflowEtape::PJ_COMPL_VALIDATION);

        if ($workflowEtapePjSaisieComplementaire != null) {
            if (!$workflowEtapePjSaisieComplementaire->isFranchie()) {
                $msg['avantRecrutement'] = 0;
                $msg['type']             = 'danger';
                $msg['text']             = "Des pièces justificatives complémentaires obligatoires n'ont pas été fournies.";
                $msgs[]                  = $msg;
                unset($msg);
            } elseif ($workflowEtapePjSaisieComplementaire->isFranchie() && $workflowEtapePjValideApresComplementaire->isFranchie()) {
                $msg['avantRecrutement'] = 0;
                $msg['type']             = 'success';
                $msg['text']             = "Toutes les pièces justificatives complémentaires obligatoires ont été fournies et validées.";
                $msgs[]                  = $msg;
                unset($msg);
            } elseif ($workflowEtapePjSaisieComplementaire->isFranchie() && !$workflowEtapePjValideApresComplementaire->isFranchie()) {
                $msg['avantRecrutement'] = 0;
                $msg['type']             = 'success';
                $msg['text']             = "Toutes les pièces justificatives complémentaires obligatoires ont été fournies.";
                $msgs[]                  = $msg;
                unset($msg);
                $msg['avantRecrutement'] = 0;
                $msg['type']             = 'warning';
                $msg['text']             = "Mais certaines doivent encore être validées par un gestionnaire.";
                $msgs[]                  = $msg;
                unset($msg);
            }
        }


        return $msgs;
    }

}
