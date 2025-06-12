<?php

namespace PieceJointe\Tbl\Process\Model;

class PieceJointe
{

    public ?string $uuid                    = null;
    public ?int    $annee                   = null;
    public ?int    $typePieceJointeId       = null;
    public ?int    $pieceJointeId           = null;
    public ?int    $intervenantId           = null;
    public bool    $demandee                = false;
    public bool    $fournie                 = false;
    public bool    $validee                 = false;
    public bool    $obligatoire             = false;
    public ?int    $dateOrigine             = null;
    public ?int    $dateValiditee           = null;
    public float   $seuilHetd               = 0;
    public bool    $demandeApresRecrutement = false;

}
