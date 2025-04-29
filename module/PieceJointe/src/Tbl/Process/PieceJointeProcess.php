<?php

namespace PieceJointe\Tbl\Process;

use Intervenant\Service\IntervenantServiceAwareTrait;
use Unicaen\BddAdmin\BddAwareTrait;
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


    protected array $piecesJointesDemandees = [];
    protected array $piecesJointesFournies  = [];
    protected array $tblData                = [];



    public function __construct()
    {

    }



    public function run(TableauBord $tableauBord, array $params = []): void
    {

        $this->getPiecesJointesDemandees($params);
        $this->getPiecesJointesFournies($params);
        $this->traitementPiecesJointes($params);
    }



    protected function getPiecesJointesDemandees(array $params): array
    {
        $params = $this->replaceIntervenantIdbyCodeIntervenantParam($params);

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
            $codeIntervenant                                                   = $pieceJointe['CODE_INTERVENANT'];
            $typePieceJointe                                                   = $pieceJointe['TYPE_PIECE_JOINTE_ID'];
            $annee                                                             = $pieceJointe['ANNEE_ID'];
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
        if (isset($params['intervenant_id'])) {
            $intervenant = $this->getServiceIntervenant()->get($params['intervenant_id']);
            $codeIntervenant = $intervenant->getCode();
            unset($params['intervenant_id']);
            $params['code_intervenant'] = $codeIntervenant;

        }
        return $params;
    }



    protected function traitementPiecesJointes(array $params): void
    {


        //On commence par traiter toutes les pièces jointes demandées
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            $uuid                                               = $pieceJointeDemandee['ANNEE_ID'] . '_' . $pieceJointeDemandee['INTERVENANT_ID'] . '_' . $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];

            $this->tblData[$uuid]['ANNEE_ID']                   = $pieceJointeDemandee['ANNEE_ID'];
            $this->tblData[$uuid]['TYPE_PIECE_JOINTE_ID']       = $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];
            $this->tblData[$uuid]['TYPE_PIECE_JOINTE_CODE']     = $pieceJointeDemandee['TYPE_PJ_CODE'];
            $this->tblData[$uuid]['PIECE_JOINTE_ID']     = null;
            $this->tblData[$uuid]['INTERVENANT_ID']             = $pieceJointeDemandee['INTERVENANT_ID'];
            $this->tblData[$uuid]['FOURNIE']                    = 0;
            $this->tblData[$uuid]['DEMANDEE']                   = 1;
            $this->tblData[$uuid]['VALIDEE']                    = null;
            $this->tblData[$uuid]['DATE_ORIGINE']               = null;
            $this->tblData[$uuid]['DATE_VALIDITEE']             = null;
            $this->tblData[$uuid]['SEUIL_HETD']          = $pieceJointeDemandee['SEUIL_HETD'];
            $this->tblData[$uuid]['OBLIGATOIRE']                = $pieceJointeDemandee['OBLIGATOIRE'];
            $this->tblData[$uuid]['DEMANDEE_APRES_RECRUTEMENT'] = $pieceJointeDemandee['DEMANDEE_APRES_RECRUTEMENT'];
            $this->sortDatas($this->tblData[$uuid]);
        }

        //On parcours maintenants les pièces jointes fournies qui sont forcément fournies l'année de leurs dépot
        foreach ($this->piecesJointesFournies as $codeIntervenant => $datas) {
            foreach ($datas as $typePieceJointeId => $piecesJointesFournies) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    $uuid                                           = $pieceJointeFournie['ANNEE_ID'] . '_' . $pieceJointeFournie['INTERVENANT_ID'] . '_' . $pieceJointeFournie['TYPE_PIECE_JOINTE_ID'];
                    if (!array_key_exists($uuid, $this->tblData)) {
                        $this->tblData[$uuid]['DEMANDEE']                = 0;
                        $this->tblData[$uuid]['OBLIGATOIRE']                = 0;

                    }
                    $this->tblData[$uuid]['ANNEE_ID']               = $pieceJointeFournie['ANNEE_ID'];
                    $this->tblData[$uuid]['TYPE_PIECE_JOINTE_ID']   = $pieceJointeFournie['TYPE_PIECE_JOINTE_ID'];
                    $this->tblData[$uuid]['TYPE_PIECE_JOINTE_CODE'] = $pieceJointeFournie['TYPE_PJ_CODE'];
                    $this->tblData[$uuid]['PIECE_JOINTE_ID'] = $pieceJointeFournie['PIECE_JOINTE_ID'];
                    $this->tblData[$uuid]['INTERVENANT_ID']         = $pieceJointeFournie['INTERVENANT_ID'];
                    $this->tblData[$uuid]['FOURNIE']                = 1;
                    $this->tblData[$uuid]['DATE_ORIGINE']           = $pieceJointeFournie['ANNEE_ID'];
                    $this->tblData[$uuid]['VALIDEE'] = (!empty($pieceJointeFournie['VALIDATION_ID']))?1:0;
                    $this->tblData[$uuid]['DATE_VALIDITEE']   = $pieceJointeFournie['DATE_VALIDITEE'];
                    $this->tblData[$uuid]['SEUIL_HETD']          = $pieceJointeFournie['SEUIL_HETD'];
                    $this->tblData[$uuid]['DEMANDEE_APRES_RECRUTEMENT']          = $pieceJointeFournie['DEMANDEE_APRES_RECRUTEMENT'];
                    $this->sortDatas($this->tblData[$uuid]);
                }
            }
        }
        //Ensuite on cherche les pièces jointes fournis sur une potentielle année postérieurs (durée de vie)
        foreach ($this->piecesJointesDemandees as $pieceJointeDemandee) {
            $piecesJointesFournies = $this->extractPiecesJointesFournies($pieceJointeDemandee['CODE_INTERVENANT'], $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID']);
            $uuid = $pieceJointeDemandee['ANNEE_ID'] . '_' . $pieceJointeDemandee['INTERVENANT_ID'] . "_" . $pieceJointeDemandee['TYPE_PIECE_JOINTE_ID'];

            if (!empty($piecesJointesFournies)) {
                foreach ($piecesJointesFournies as $pieceJointeFournie) {
                    if ((int)$pieceJointeDemandee['ANNEE_ID'] > (int)$pieceJointeFournie['ANNEE_ID']) {
                        if (//1 - Si la pièce jointe est validée
                            !empty($pieceJointeFournie['VALIDATION_ID']) &&
                            //2 -Si la date de validité de la pièce jointe est strictement supérieure à l'année où elle est demandée
                            (int)$pieceJointeFournie['DATE_VALIDITEE'] > (int)$pieceJointeDemandee['ANNEE_ID'] &&
                            //3 - Si l'année de la pièce fournie est strictement supérieure au maximum de l'ancienneté
                            //de la pièce possible par rapport à la durée de vie paramétrée sur l'année où elle est demandée
                            (int)$pieceJointeFournie['ANNEE_ID'] > ((int)$pieceJointeDemandee['ANNEE_ID']-(int)$pieceJointeDemandee['DUREE_VIE']) &&
                            //4 - Si la date d'archive de la pièce fournie est strictement supérieure à l'année où elle est demandée
                            ((int)$pieceJointeFournie['DATE_ARCHIVE'] > (int)$pieceJointeDemandee['ANNEE_ID'] ||
                                empty($pieceJointeFournie['DATE_ARCHIVE']))) {
                            $this->tblData[$uuid]['PIECE_JOINTE_ID'] = $pieceJointeFournie['PIECE_JOINTE_ID'];
                            $this->tblData[$uuid]['DATE_ORIGINE']    = $pieceJointeFournie['ANNEE_ID'];
                            $this->tblData[$uuid]['DATE_VALIDITEE']   = $pieceJointeFournie['DATE_VALIDITEE'];
                            $this->tblData[$uuid]['FOURNIE']         = 1;
                            $this->tblData[$uuid]['VALIDEE'] = 1;

                        }
                    }

                }
            }
            $this->sortDatas($this->tblData[$uuid]);
        }
        ksort($this->tblData);

    }

    private function sortDatas(array &$datas): void
    {

        uksort($datas, function ($a, $b) {
            $ordre = [
                'ANNEE_ID',
                'TYPE_PIECE_JOINTE_ID',
                'TYPE_PIECE_JOINTE_CODE',
                'PIECE_JOINTE_ID',
                'INTERVENANT_ID',
                'DEMANDEE',
                'FOURNIE',
                'VALIDEE',
                'OBLIGATOIRE',
                'DATE_ORIGINE',
                'DATE_VALIDITEE',
                'SEUIL_HETD',
                'DEMANDEE_APRES_RECRUTEMENT'
            ];
            $posA  = array_search($a, $ordre);
            $posB  = array_search($b, $ordre);
            return $posA - $posB;
        });

    }
}
