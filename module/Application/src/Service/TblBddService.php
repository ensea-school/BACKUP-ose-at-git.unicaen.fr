<?php

namespace Application\Service;

use UnicaenTbl\Service\BddService;

/**
 * Depuis unicaen/tbl 6.3.1, injectKey() met les clés en minuscules alors que les vues TBL d'OSE
 * portent des marqueurs en majuscules (/*@INTERVENANT_ID=...*\/) : aucun filtre n'était plus injecté
 * et chaque calcul portait sur toute la base.
 */
class TblBddService extends BddService
{

    public function injectKey(string $query, array $key): string
    {
        $query = preg_replace_callback('#/\*@(\w+)#', fn($m) => '/*@' . strtolower($m[1]), $query);

        return parent::injectKey($query, $key);
    }
}
