<?php

namespace PieceJointe\Tbl\Process;

use Application\Service\Traits\AnneeServiceAwareTrait;
use Intervenant\Service\IntervenantServiceAwareTrait;
use PieceJointe\Tbl\Process\Model\PieceJointe;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Event;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of PieceJointeProcess
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PieceJointeProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;
    use IntervenantServiceAwareTrait;
    use AnneeServiceAwareTrait;


    protected array $piecesJointesDemandees = [];
    protected array $piecesJointesFournies  = [];
    /** @var array|PieceJointe[][] */
    private array   $piecesJointes = [];
    protected array $tblData       = [];



    public function __construct()
    {

    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {
        mpg_lower($params);
        $this->getPiecesJointesDemandees($params);
        $this->getPiecesJointesFournies($params);
        $this->traitementPiecesJointes($params);
        $this->exporterPiecesJointes($params);
        $this->enregistrement($tableauBord, $params);
    }



    protected function getPiecesJointesDemandees(array $params): array
    {
        mpg_lower($params);
        $definition                = $this->getServiceBdd()->getViewDefinition('V_TBL_PIECE_JOINTE_DEMANDE');
        $sqlPiecesJointesDemandees = 'SELECT * FROM ('
                                     . $this->getServiceBdd()->injectKey($definition, $params)
                                     . ') t '
                                     . $this->getServiceBdd()->makeWhere($params);


        $piecesJointesDemandees = $this->getBdd()->selectEach($sqlPiecesJointesDemandees);

        while ($pieceJointe = $piecesJointesDemandees->next()) {
            $this->piecesJointesDemandees[] = $pieceJointe;
        }

        unset($piecesJointesDemandees);

        return $this->piecesJointesDemandees;
    }



    protected function getPiecesJointesFournies(array $params): array
    {
        mpg_lower($params);
        $params = $this->replaceIntervenantIdbyCodeIntervenantParam($params);

        $definition               = $this->getServiceBdd()->getViewDefinition('V_TBL_PIECE_JOINTE_FOURNIE');
        $sqlPiecesJointesFournies = 'SELECT * FROM ('
                                    . $this->getServiceBdd()->injectKey($definition, $params)
                                    . ') t '
                                    . $this->getServiceBdd()->makeWhere($params);


        $piecesJointesFournies = $this->getBdd()->selectEach($sqlPiecesJointesFournies);

        while ($pieceJointe = $piecesJointesFournies->next()) {
            mpg_lower($pieceJointe);
            $codeIntervenant                                                         = $pieceJointe['code_intervenant'];
            $typePieceJointe                                                         = $pieceJointe['type_piece_jointe_id'];
            $annee                                                                   = $pieceJointe['annee_id'];
            $this->piecesJointesFournies[$codeIntervenant][$typePieceJointe][$annee] = $pieceJointe;
            ksort($this->piecesJointesFournies[$codeIntervenant][$typePieceJointe]);
        }

        unset($piecesJointesFournies);
        return $this->piecesJointesFournies;


    }



    protected function extractPiecesJointesFournies(string $codeIntervenant, int $typePieceJointeId): array
    {
        $piecesJointesFournies = [];
        if (array_key_exists($codeIntervenant, $this->piecesJointesFournies)) {
            $datas = $this->piecesJointesFournies[$codeIntervenant];
            if (array_key_exists($typePieceJointeId, $datas)) {
                $piecesJointesFournies = $datas[$typePieceJointeId];
            }
        }
        return $piecesJointesFournies;
    }



    protected function replaceIntervenantIdbyCodeIntervenantParam(array $params): array
    {
        mpg_lower($params);
        if (isset($params['intervenant_id'])) {
            $intervenant     = $this->getServiceIntervenant()->get($params['intervenant_id']);
            $codeIntervenant = $intervenant->getCode();
            unset($params['intervenant_id']);
            $params['code_intervenant'] = $codeIntervenant;

        }
        if (isset($params['annee_id'])) {
            unset($params['annee_id']);
        }

        return $params;
    }



    protected function traitementPiecesJointes(array $params): void
    {
        //On commence par traiter toutes les pièces jointes demandées
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            mpg_lower($pieceJointeDemandee);
            $uuid                                 = $pieceJointeDemandee['annee_id'] . '_' . $pieceJointeDemandee['intervenant_id'] . '_' . $pieceJointeDemandee['type_piece_jointe_id'];
            $pieceJointe                          = new PieceJointe();
            $pieceJointe->uuid                    = $uuid;
            $pieceJointe->annee                   = $pieceJointeDemandee['annee_id'];
            $pieceJointe->typePieceJointeId       = $pieceJointeDemandee['type_piece_jointe_id'];
            $pieceJointe->intervenantId           = $pieceJointeDemandee['intervenant_id'];
            $pieceJointe->demandee                = true;
            $pieceJointe->fournie                 = false;
            $pieceJointe->obligatoire             = true;
            $pieceJointe->demandeApresRecrutement = $pieceJointeDemandee['demandee_apres_recrutement'];
            $this->piecesJointes[$uuid]           = $pieceJointe;

        }
        //On parcourt maintenant les pièces jointes fournies pour voir si elles ont été fourni sur l'année demandée
        foreach ($this->piecesJointesFournies as $codeIntervenant => $datas) {
            foreach ($datas as $typePieceJointeId => $piecesJointesFournies) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    mpg_lower($pieceJointeFournie);
                    $uuid = $pieceJointeFournie['annee_id'] . '_' . $pieceJointeFournie['intervenant_id'] . '_' . $pieceJointeFournie['type_piece_jointe_id'];
                    if (array_key_exists($uuid, $this->piecesJointes)) {
                        $this->piecesJointes[$uuid]->pieceJointeId = $pieceJointeFournie['piece_jointe_id'];
                        $this->piecesJointes[$uuid]->fournie       = 1;
                        $this->piecesJointes[$uuid]->validee       = !empty($pieceJointeFournie['validation_id']);
                        $this->piecesJointes[$uuid]->dateValiditee = $pieceJointeFournie['date_validitee'];
                    }
                }
            }
        }
        //Ensuite on cherche les pièces jointes fournies sur une potentielle année postérieure (durée de vie)
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            mpg_lower($pieceJointeDemandee);
            $codeIntervenant   = $pieceJointeDemandee['code_intervenant'];
            $typePieceJointeId = $pieceJointeDemandee['type_piece_jointe_id'];
            $uuid              = $pieceJointeDemandee['annee_id'] . '_' . $pieceJointeDemandee['intervenant_id'] . "_" . $pieceJointeDemandee['type_piece_jointe_id'];
            //Piece jointe demandée déjà fournie donc on passe
            if ($this->piecesJointes[$uuid]->fournie == 1) {
                continue;
            }
            $piecesJointesFournies = $this->extractPiecesJointesFournies($codeIntervenant, $typePieceJointeId);
            //J'ai des pieces fournies antérieurement
            if (!empty($piecesJointesFournies)) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    mpg_lower($pieceJointeFournie);
                    //L'année de la pièce jointe fournie est postérieur à l'année de la pièce jointe demandée
                    if ((int)$pieceJointeDemandee['annee_id'] >= (int)$pieceJointeFournie['annee_id'] && !empty($pieceJointeFournie['validation_id'])) {
                        if (//1 -Si la date de validité de la pièce jointe est strictement supérieure à l'année où elle est demandée
                            (int)$pieceJointeFournie['date_validitee'] > (int)$pieceJointeDemandee['annee_id'] &&
                            //2 - L'année de la pièce jointe fournie correspond au critère de durée de vie de la pièce jointe demandée
                            (int)$pieceJointeFournie['annee_id'] > round((int)$pieceJointeDemandee['annee_id'] - (int)$pieceJointeDemandee['duree_vie']) &&
                            //3 - Si la date d'archive de la pièce fournie est strictement supérieure à l'année où elle est demandée
                            ((int)$pieceJointeFournie['date_archive'] > (int)$pieceJointeDemandee['annee_id'] ||
                             empty($pieceJointeFournie['date_archive']))) {
                            $this->piecesJointes[$uuid]->pieceJointeId = $pieceJointeFournie['piece_jointe_id'];
                            $this->piecesJointes[$uuid]->dateValiditee = $pieceJointeFournie['date_validitee'];
                            $this->piecesJointes[$uuid]->fournie       = 1;
                            $this->piecesJointes[$uuid]->validee       = !empty($pieceJointeFournie['validation_id']);

                        }
                    }
                }

            }
        }
        ksort($this->piecesJointes);

    }



    public function exporterPiecesJointes(array $params): void
    {
        foreach ($this->piecesJointes as $uuid => $piecesJointe) {
            $this->tblData[] = [
                'annee_id'                  => $piecesJointe->annee,
                'type_piece_jointe_id'      => $piecesJointe->typePieceJointeId,
                'piece_jointe_id'           => $piecesJointe->pieceJointeId,
                'intervenant_id'            => $piecesJointe->intervenantId,
                'demandee'                  => $piecesJointe->demandee,
                'fournie'                   => $piecesJointe->fournie,
                'validee'                   => $piecesJointe->validee,
                'obligatoire'               => $piecesJointe->obligatoire,
                'date_validitee'            => $piecesJointe->dateValiditee,
                'demande_apres_recrutement' => $piecesJointe->demandeApresRecrutement,
            ];
        }

        mpg_upper($this->tblData);

    }



    public function enregistrement(TableauBord $tableauBord, array $params): void
    {
        mpg_lower($params);

        $key = $tableauBord->getOption('key');

        $table = $this->getBdd()->getTable('TBL_PIECE_JOINTE');

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['intervenant_id']),
            'callback'           => function (string $action, int $progress, int $total) use ($tableauBord) {
                $tableauBord->onAction(Event::PROGRESS, $progress, $total);
            },
        ];
        try {
            $table->merge($this->tblData, $key, $options);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }
        $this->tblData = [];
    }

}
