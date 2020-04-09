<?php





class AgrementPassageDureeVie extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Initalisation de la durée de vie des agréments";
    }



    public function utile(): bool
    {
        return
            $this->manager->hasOldColumn('TYPE_AGREMENT_STATUT', 'PREMIER_RECRUTEMENT')
            && $this->manager->hasNewColumn('TYPE_AGREMENT_STATUT', 'DUREE_VIE');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    protected function before()
    {
        $this->manager->sauvegarderTable('TYPE_AGREMENT_STATUT', 'TAS_AJOUT_DUREE_VIE');
    }



    protected function after()
    {
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();

        $sql = '
        UPDATE TYPE_AGREMENT_STATUT SET DUREE_VIE = (
          SELECT CASE WHEN PREMIER_RECRUTEMENT = 1 THEN 99 ELSE 1 END FROM TAS_AJOUT_DUREE_VIE O WHERE O.ID = TYPE_AGREMENT_STATUT.ID
        )';

        $bdd->exec($sql);
        $this->manager->supprimerSauvegarde('TAS_AJOUT_DUREE_VIE');

        $console->println("Calcul du tableau de bord agrement");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');

        $console->println("");

        /* On fournie et on valide de force les PJ qui n'étaient pas tout le temps demandées avant */
        $sql   = '
        SELECT
          a.structure_id,
          i.id intervenant_id,
          a.type_agrement_id,
          P.VALEUR UTILISATEUR_ID
        FROM 
          tbl_agrement a
          JOIN intervenant i ON i.id = a.intervenant_id AND i.histo_destruction IS NULL
          JOIN PARAMETRE P ON P.NOM = \'oseuser\'
        WHERE
          a.duree_vie = 99 
          AND a.agrement_id IS NULL
          AND I.PREMIER_RECRUTEMENT = 0
        ';
        $r     = $bdd->select($sql);
        $count = 0;
        foreach ($r as $i) {
            $count++;
            $o = ['histo-user-id' => $i['UTILISATEUR_ID']];

            $agrement = [
                'ID'               => $bdd->sequenceNextVal('AGREMENT_ID_SEQ'),
                'STRUCTURE_ID'     => $i['STRUCTURE_ID'],
                'INTERVENANT_ID'   => $i['INTERVENANT_ID'],
                'DATE_DECISION'    => new \DateTime(),
                'TYPE_AGREMENT_ID' => $i['TYPE_AGREMENT_ID'],
            ];
            $bdd->getTable('AGREMENT')->insert($agrement, $o);

            $console->print("Insertion des agréments manquants : $count / " . count($r) . "\r");
        }

        $console->println("Nouveau calcul du tableau de bord agrement");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');

        $console->println("Nouveau calcul du tableau de bord workflow");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'workflow\'); END;');

        $console->println("Terminé");
    }

}