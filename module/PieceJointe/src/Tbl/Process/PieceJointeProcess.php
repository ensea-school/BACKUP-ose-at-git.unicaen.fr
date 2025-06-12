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
        $this->getPiecesJointesDemandees($params);
        $this->getPiecesJointesFournies($params);
        $this->traitementPiecesJointes($params);
        $this->exporterPiecesJointes($params);
        $this->enregistrement($tableauBord, $params);
    }



    protected function getPiecesJointesDemandees(array $params): array
    {
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

        $params = $this->replaceIntervenantIdbyCodeIntervenantParam($params);

        $definition               = $this->getServiceBdd()->getViewDefinition('V_TBL_PIECE_JOINTE_FOURNIE');
        $sqlPiecesJointesFournies = 'SELECT * FROM ('
                                    . $this->getServiceBdd()->injectKey($definition, $params)
                                    . ') t '
                                    . $this->getServiceBdd()->makeWhere($params);


        $piecesJointesFournies = $this->getBdd()->selectEach($sqlPiecesJointesFournies);

        while ($pieceJointe = $piecesJointesFournies->next()) {
            $codeIntervenant                                                         = $pieceJointe['CODE_INTERVENANT'];
            $typePieceJointe                                                         = $pieceJointe['TYPE_PIECE_JOINTE_ID'];
            $annee                                                                   = $pieceJointe['ANNEE_ID'];
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
        if (isset($params['INTERVENANT_ID'])) {
            $intervenant     = $this->getServiceIntervenant()->get($params['INTERVENANT_ID']);
            $codeIntervenant = $intervenant->getCode();
            unset($params['INTERVENANT_ID']);
            $params['CODE_INTERVENANT'] = $codeIntervenant;

        }
        if (isset($params['ANNEE_ID'])) {
            unset($params['ANNEE_ID']);
        }

        return $params;
    }



    protected function traitementPiecesJointes(array $params): void
    {
        //On commence par traiter toutes les pièces jointes demandées
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            $uuid                                 = $pieceJointeDemandee['ANNEE_ID'] . '_' . $pieceJointeDemandee['INTERVENANT_ID'] . '_' . $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];
            $pieceJointe                          = new PieceJointe();
            $pieceJointe->uuid                    = $uuid;
            $pieceJointe->annee                   = $pieceJointeDemandee['ANNEE_ID'];
            $pieceJointe->typePieceJointeId       = $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];
            $pieceJointe->intervenantId           = $pieceJointeDemandee['INTERVENANT_ID'];
            $pieceJointe->demandee                = true;
            $pieceJointeFournie                   = false;
            $pieceJointe->seuilHetd               = $pieceJointeDemandee['SEUIL_HETD'];
            $pieceJointe->obligatoire             = $pieceJointeDemandee['OBLIGATOIRE'];
            $pieceJointe->demandeApresRecrutement = $pieceJointeDemandee['DEMANDEE_APRES_RECRUTEMENT'];
            $this->piecesJointes[$uuid]           = $pieceJointe;
        }
        //On parcourt maintenant les pièces jointes fournies pour voir si elles ont été fourni sur l'année demandée
        foreach ($this->piecesJointesFournies as $codeIntervenant => $datas) {
            foreach ($datas as $typePieceJointeId => $piecesJointesFournies) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    $uuid = $pieceJointeFournie['ANNEE_ID'] . '_' . $pieceJointeFournie['INTERVENANT_ID'] . '_' . $pieceJointeFournie['TYPE_PIECE_JOINTE_ID'];
                    if (array_key_exists($uuid, $this->piecesJointes)) {
                        $this->piecesJointes[$uuid]->pieceJointeId = $pieceJointeFournie['PIECE_JOINTE_ID'];
                        $this->piecesJointes[$uuid]->fournie       = 1;
                        $this->piecesJointes[$uuid]->dateOrigine   = $pieceJointeFournie['ANNEE_ID'];
                        $this->piecesJointes[$uuid]->validee       = !empty($pieceJointeFournie['VALIDATION_ID']);
                        $this->piecesJointes[$uuid]->dateValiditee = $pieceJointeFournie['DATE_VALIDITEE'];
                    }
                }
            }
        }
        //Ensuite on cherche les pièces jointes fournies sur une potentielle année postérieure (durée de vie)
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            $codeIntervenant   = $pieceJointeDemandee['CODE_INTERVENANT'];
            $typePieceJointeId = $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];
            $uuid              = $pieceJointeDemandee['ANNEE_ID'] . '_' . $pieceJointeDemandee['INTERVENANT_ID'] . "_" . $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];
            //Piece jointe demandée déjà fournie donc on passe
            if ($this->piecesJointes[$uuid]->fournie == 1) {
                continue;
            }
            $piecesJointesFournies = $this->extractPiecesJointesFournies($codeIntervenant, $typePieceJointeId);
            //J'ai des pieces fournies antérieurement
            if (!empty($piecesJointesFournies)) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    //L'année de la pièce jointe fournie est postérieur à l'année de la pièce jointe demandée
                    if ((int)$pieceJointeDemandee['ANNEE_ID'] > (int)$pieceJointeFournie['ANNEE_ID']) {
                        if (//1 -Si la date de validité de la pièce jointe est strictement supérieure à l'année où elle est demandée
                            (int)$pieceJointeFournie['DATE_VALIDITEE'] > (int)$pieceJointeDemandee['ANNEE_ID'] &&
                            //2 - L'année de la pièce jointe fournie correspond au critère de durée de vie de la pièce jointe demandée
                            (int)$pieceJointeFournie['ANNEE_ID'] > round((int)$pieceJointeDemandee['ANNEE_ID'] - (int)$pieceJointeDemandee['DUREE_VIE']) &&
                            //3 - Si la date d'archive de la pièce fournie est strictement supérieure à l'année où elle est demandée
                            ((int)$pieceJointeFournie['DATE_ARCHIVE'] > (int)$pieceJointeDemandee['ANNEE_ID'] ||
                             empty($pieceJointeFournie['DATE_ARCHIVE']))) {
                            $this->piecesJointes[$uuid]->pieceJointeId = $pieceJointeFournie['PIECE_JOINTE_ID'];
                            $this->piecesJointes[$uuid]->dateOrigine   = $pieceJointeFournie['ANNEE_ID'];
                            $this->piecesJointes[$uuid]->dateValiditee = $pieceJointeFournie['DATE_VALIDITEE'];
                            $this->piecesJointes[$uuid]->fournie       = 1;
                            $this->piecesJointes[$uuid]->validee       = !empty($pieceJointeFournie['VALIDATION_ID']);

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
                'ANNEE_ID'                   => $piecesJointe->annee,
                'TYPE_PIECE_JOINTE_ID'       => $piecesJointe->typePieceJointeId,
                'PIECE_JOINTE_ID'            => $piecesJointe->pieceJointeId,
                'INTERVENANT_ID'             => $piecesJointe->intervenantId,
                'DEMANDEE'                   => $piecesJointe->demandee,
                'FOURNIE'                    => $piecesJointe->fournie,
                'VALIDEE'                    => $piecesJointe->validee,
                'OBLIGATOIRE'                => $piecesJointe->obligatoire,
                'DATE_ORIGINE'               => $piecesJointe->dateOrigine,
                'DATE_VALIDITEE'             => $piecesJointe->dateValiditee,
                'SEUIL_HETD'                 => $piecesJointe->seuilHetd,
                'DEMANDEE_APRES_RECRUTEMENT' => $piecesJointe->demandeApresRecrutement,
            ];
        }

    }



    public function enregistrement(TableauBord $tableauBord, array $params): void
    {


        $key = $tableauBord->getOption('key');

        $table = $this->getBdd()->getTable('TBL_PIECE_JOINTE');

        $options = [
            'where'              => $params,
            'return-insert-data' => false,
            'transaction'        => !isset($params['INTERVENANT_ID']),
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
