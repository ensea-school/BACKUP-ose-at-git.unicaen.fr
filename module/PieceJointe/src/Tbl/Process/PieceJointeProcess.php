<?php

namespace PieceJointe\Tbl\Process;

use Dossier\Tbl\Process\Sub\CalculateurCompletude;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenTbl\Process\ProcessInterface;
use UnicaenTbl\Service\BddServiceAwareTrait;
use UnicaenTbl\TableauBord;

/**
 * Description of PaiementProcess
 *
 * @author Antony Le Courtes <antony.lecourtes at unicaen.fr>
 */
class PieceJointeProcess implements ProcessInterface
{
    use BddServiceAwareTrait;
    use BddAwareTrait;


    protected array $piecesJointes = [];
    protected array $tblData  = [];



    public function __construct()
    {

    }


    public function run(TableauBord $tableauBord, array $params = []): void
    {


    }







}
