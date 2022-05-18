<?php





class v18IndicateursNumRen extends AbstractMigration
{

    public function description(): string
    {
        return "Renomage des indicateurs";
    }



    public function utile(): bool
    {
        $bdd = $this->manager->getBdd();

        $res = $bdd->select("select numero from indicateur where libelle_singulier like '%disparu%'");

        return (int)($res[0]['NUMERO'] ?? 0) == 510;
    }



    public function before()
    {
        $numeros = [
            410 => 110,
            420 => 120,

            1010 => 210,
            1011 => 220,
            1020 => 230,
            1021 => 240,

            200 => 330,
            210 => 310,
            220 => 320,

            310 => 410,
            320 => 420,
            330 => 430,
            340 => 440,
            350 => 450,
            360 => 460,
            361 => 470,
            370 => 480,
            380 => 490,

            610 => 510,
            620 => 520,
            630 => 530,
            640 => 540,
            650 => 550,
            660 => 560,
            670 => 570,

            510 => 610,
            710 => 620,
            720 => 630,
            730 => 640,
            740 => 650,

            110 => 710,
            120 => 720,
            130 => 730,

            1110 => 810,
            1111 => 820,
            1120 => 830,
            1121 => 840,

            910 => 910,
            810 => 920,
            920 => 930,
            820 => 940,
        ];


        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();


        $ids = [];
        $res = $bdd->select('SELECT ID, NUMERO FROM indicateur');
        foreach ($res as $r) {
            $ids[(int)$r['NUMERO']] = (int)$r['ID'];
        }

        // On gère les suppression d'anciens types d'indicateurs
        if ($this->manager->hasColumn('INDICATEUR', 'TYPE_INDICATEUR_ID')) {
            $bdd->exec("UPDATE indicateur SET type_indicateur_id = 7 WHERE type_indicateur_id = 5");
            $bdd->exec("UPDATE indicateur SET type_indicateur_id = 10 WHERE type_indicateur_id = 11");
        }

        foreach ($numeros as $ancien => $nouveau) {
            $id  = $ids[$ancien];
            $sql = "UPDATE indicateur SET numero = $nouveau WHERE id = $id";
            $bdd->exec($sql);
        }
    }
}