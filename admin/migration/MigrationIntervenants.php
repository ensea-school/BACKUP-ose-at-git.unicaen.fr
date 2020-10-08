<?php





class MigrationIntervenants extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration des intervenants";
    }



    public function utile(): bool
    {
        return true;

        return $this->manager->hasOldColumn('INTERVENANT', 'NUMERO_INSEE_CLE');
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
        $bdd = $this->manager->getBdd();
        $c   = (int)$bdd->select('SELECT count(*) cc FROM intervenant WHERE code IS NULL')[0]['CC'];
        if ($c > 0) {
            $this->manager->getOseAdmin()->getConsole()->printDie(
                'La colonne INTERVENANT.CODE comporte des valeurs NULL :'
                . ' ceci ne peut plus être permis par la version 15 de OSE.'
                . ' Veuillez renseigner manuellement cette colonne,'
                . ' puis retentez la mise à jour de la base de données (./bin/ose update-bdd)'
            );
        }

        $this->manager->sauvegarderTable('INTERVENANT', 'INTERVENANT_SAVE');
        $this->manager->sauvegarderTable('ADRESSE_INTERVENANT', 'ADRESSE_INTERVENANT_SAVE');


        $bdd->exec('UPDATE INTERVENANT SET NUMERO_INSEE_PROVISOIRE = 0 WHERE NUMERO_INSEE_PROVISOIRE IS NULL');
    }



    protected function after()
    {
        $bdd = $this->manager->getBdd();

        $sql = "
        SELECT 
          i.id,
          i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
          i.tel_mobile, i.email,
          i.dep_naissance_id,
          i.ville_naissance_libelle,
          ai.tel_domicile,
          ai.mention_complementaire,
          ai.batiment,
          ai.localite,
          ai.no_voie,
          ai.nom_voie,
          ai.code_postal,
          ai.ville,
          ai.pays_libelle,
          p.id adresse_pays_id
        FROM 
          intervenant_save i
          LEFT JOIN adresse_intervenant_save ai ON ai.intervenant_id = i.id
          LEFT JOIN pays p ON OSE_DIVERS.STR_REDUCE(p.libelle) = OSE_DIVERS.STR_REDUCE(ai.pays_libelle)
        ";
        $bdd->trigger()->disable('F_INTERVENANT');
        $bdd->trigger()->disable('F_INTERVENANT_S');
        $bdd->trigger()->disable('INTERVENANT_CK');
        //$intervenants = $bdd->select($sql, [], ['fetch' => $bdd::FETCH_EACH]);
        //while ($i = $intervenants->next()) {
        $bdd->logBegin("\nConversion des adresses dans le nouveau format");
        $intervenants = $bdd->select($sql, []);
        $count        = count($intervenants);
        $iTable       = $bdd->getTable('INTERVENANT');
        foreach ($intervenants as $ind => $i) {
            [$numVoie, $numVoieCompl] = $this->decoupageNumVoie($i['NO_VOIE']);

            $bdd->logMsg("Traitement de l'intervenant $ind/$count, ID=" . $i['ID'], true);
            $data = [
                'NUMERO_INSEE'             => $i['NUMERO_INSEE'] . $i['NUMERO_INSEE_CLE'],
                'TEL_PERSO'                => $i['TEL_MOBILE'],
                'DEPARTEMENT_NAISSANCE_ID' => $i['DEP_NAISSANCE_ID'],
                'EMAIL_PRO'                => $i['EMAIL'],
                'COMMUNE_NAISSANCE'        => $i['VILLE_NAISSANCE_LIBELLE'],
                'TEL_PERSO'                => $i['TEL_DOMICILE'],
                'ADRESSE_PRECISIONS'       => trim($i['MENTION_COMPLEMENTAIRE'] . ' ' . $i['BATIMENT']),
                'ADRESSE_LIEU_DIT'         => $i['LOCALITE'],
                'ADRESSE_NUMERO'           => $numVoie,
                'ADRESSE_NUMERO_COMPL_ID'  => $numVoieCompl,
                //'ADRESSE_VOIRIE_ID'        => null,
                'ADRESSE_VOIE'             => $i['NOM_VOIE'],
                'ADRESSE_CODE_POSTAL'      => $i['CODE_POSTAL'],
                'ADRESSE_COMMUNE'          => trim($i['VILLE'] . ' ' . ($i['ADRESSE_PAYS_ID'] ? null : $i['PAYS_LIBELLE'])),
                'ADRESSE_PAYS_ID'          => $i['ADRESSE_PAYS_ID'],
            ];
            try {
                $iTable->update($data, ['ID' => $i['ID']]);
            } catch (\Exception $e) {
                $bdd->logError($e);
                var_dump($data);
            }
        }

        $bdd->trigger()->enable('F_INTERVENANT');
        $bdd->trigger()->enable('F_INTERVENANT_S');
        $bdd->trigger()->enable('INTERVENANT_CK');

        $bdd->logEnd('Adresses transférées');
        $this->manager->supprimerSauvegarde('INTERVENANT_SAVE');
        $this->manager->supprimerSauvegarde('ADRESSE_INTERVENANT_SAVE');
    }



    protected function decoupageNumVoie(?string $numVoie): array
    {
        $num   = $numVoie;
        $compl = null;

        $compls = [
            'BIS' => 2,
            'Bis' => 2,
            'bis' => 2,

            'TER' => 3,
            'Ter' => 3,
            'ter' => 3,

            'QUATER' => 4,
            'QUA'    => 4,
            'qua'    => 4,
            'Quater' => 4,
            'quater' => 4,

            'QUINQUIES' => 5,
            'QUIN'      => 5,
            'quin'      => 5,
            'Quinquies' => 5,
            'quinquies' => 5,

            'B' => 2,
            'b' => 2,
            'T' => 3,
            't' => 3,
            'Q' => 4,
            'q' => 4,
            'C' => 5,
            'c' => 5,
        ];
        foreach ($compls as $c => $cid) {
            if (false !== strpos($num, $c)) {
                $num   = trim(str_replace($c, '', $num));
                $compl = $cid;
                break;
            }
        }

        return [$num, $compl];
    }
}

