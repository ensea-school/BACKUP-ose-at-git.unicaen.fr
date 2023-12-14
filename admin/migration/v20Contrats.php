<?php





class v20Contrats extends AbstractMigration
{

    public function description(): string
    {
        return "Transformation des modèles de contrats en états de sortie";
    }



    public function utile(): bool
    {
        return $this->manager->hasOld('table', 'MODELE_CONTRAT');
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        $c->begin("Convertion des contrats de travail en états de sortie");

        $this->manager->sauvegarderTable('MODELE_CONTRAT', 'SAVE_MODELE_CONTRAT');
        // On supprime l'ancienne table afin de ne jamais recommencer la migration, puis on travaille sur la sauvegarde

        $bdd->table()->drop('MODELE_CONTRAT');

        $modeles = $bdd->select("SELECT * FROM SAVE_MODELE_CONTRAT");

        $etatsSortie = [];
        $statuts     = [];

        $sts = $bdd->select('select distinct code from statut WHERE histo_destruction IS NULL');
        foreach ($sts as $st) {
            $statuts[$st['CODE']] = null;
        }

        foreach ($modeles as $modele) {
            $id       = (int)$modele['ID'];
            $code     = 'CONTRAT_' . $id;
            $libelle  = 'Contrat de travail - ' . $modele['LIBELLE'];
            $statutId = $modele['STATUT_ID'] ? (int)$modele['STATUT_ID'] : null;
            $fichier  = $modele['FICHIER'];
            $requete  = $modele['REQUETE'] ?: 'SELECT * FROM v_contrat_main';

            if ($statutId) {
                $statutCode           = $bdd->select("SELECT code FROM statut WHERE id = $statutId")[0]['CODE'];
                $statuts[$statutCode] = $code;
            } else {
                $statutCode = null;
                foreach ($statuts as $scode => $sc) {
                    if (empty($sc)) {
                        $statuts[$scode] = $code;
                    }
                }
            }

            $etatSortie = [
                'CODE'           => $code,
                'LIBELLE'        => $libelle,
                'FICHIER'        => $fichier,
                'REQUETE'        => $requete,
                'PDF_TRAITEMENT' => '$mainData    = reset($data);
$data        = [];
$exemplaires = [];

for ($i = 1; $i <= 3; $i++) {
    $exemplaire = $mainData[\'exemplaire\' . $i] ?? \'0\';
    if ($exemplaire !== \'0\') {
        $exemplaires[$i] = $exemplaire;
    }
    unset($mainData[\'exemplaire\' . $i]);
}

foreach ($exemplaires as $exemplaire) {
    $newExemplaire               = $mainData;
    $newExemplaire[\'exemplaire\'] = $exemplaire;
    $data[]                      = $newExemplaire;
}

return $data;',
                'CLE'            => 'CONTRAT_ID',
                'BLOC1_NOM'      => 'serviceCode',
                'BLOC1_ZONE'     => 'table:table-row',
                'BLOC1_REQUETE'  => 'SELECT * FROM V_CONTRAT_SERVICES',
            ];

            $etatsSortie[] = $etatSortie;
        }

        $c->msg("Création des nouveaux états");
        foreach ($etatsSortie as $etatSortie) {
            $bdd->getTable('ETAT_SORTIE')->insert($etatSortie);
            foreach ($statuts as $scode => $setat) {
                if ($setat === $etatSortie['CODE']) {
                    $statuts[$scode] = $etatSortie['ID'];
                }
            }
        }

        $c->msg("Configuration des statuts");
        foreach ($statuts as $scode => $modeleId) {
            $bdd->exec("UPDATE STATUT SET contrat_etat_sortie_id = $modeleId WHERE CODE = :code", ['code' => $scode]);
        }

        $c->end("Convertion terminée");
    }
}