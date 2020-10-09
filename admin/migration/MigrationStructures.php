<?php





class MigrationStructures extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration des structures";
    }



    public function utile(): bool
    {
        return $this->manager->hasOld('table', 'ADRESSE_STRUCTURE');
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

        $this->manager->sauvegarderTable('STRUCTURE', 'STRUCTURE_SAVE');
        $this->manager->sauvegarderTable('ADRESSE_STRUCTURE', 'ADRESSE_STRUCTURE_SAVE');
    }



    protected function after()
    {
        $bdd = $this->manager->getBdd();

        $sql = "
        SELECT 
          s.id,
          adr.localite,
          adr.no_voie,
          adr.nom_voie,
          adr.code_postal,
          adr.ville,
          adr.pays_libelle,
          p.id adresse_pays_id
        FROM 
          structure_save s
          LEFT JOIN adresse_structure_save adr ON adr.structure_id = s.id AND adr.principale = 1
          LEFT JOIN pays p ON OSE_DIVERS.STR_REDUCE(p.libelle) = OSE_DIVERS.STR_REDUCE(adr.pays_libelle)
        ";

        $bdd->logBegin("\nConversion des adresses dans le nouveau format");
        $structures = $bdd->select($sql, []);
        $count      = count($structures);
        $sTable     = $bdd->getTable('STRUCTURE');
        foreach ($structures as $ind => $s) {
            [$numVoie, $numVoieCompl] = $this->decoupageNumVoie($s['NO_VOIE']);

            $bdd->logMsg("Traitement de l'intervenant $ind/$count, ID=" . $s['ID'], true);
            $data = [
                'ADRESSE_PRECISIONS'      => null,
                'ADRESSE_LIEU_DIT'        => $s['LOCALITE'],
                'ADRESSE_NUMERO'          => $numVoie,
                'ADRESSE_NUMERO_COMPL_ID' => $numVoieCompl,
                //'ADRESSE_VOIRIE_ID'        => null,
                'ADRESSE_VOIE'            => $s['NOM_VOIE'],
                'ADRESSE_CODE_POSTAL'     => $s['CODE_POSTAL'],
                'ADRESSE_COMMUNE'         => trim($s['VILLE'] . ' ' . ($s['ADRESSE_PAYS_ID'] ? null : $s['PAYS_LIBELLE'])),
                'ADRESSE_PAYS_ID'         => $s['ADRESSE_PAYS_ID'],
            ];
            try {
                $sTable->update($data, ['ID' => $s['ID']]);
            } catch (\Exception $e) {
                $bdd->logError($e);
                var_dump($data);
            }
        }

        $bdd->logEnd('Adresses transférées');
        $this->manager->supprimerSauvegarde('STRUCTURE_SAVE');
        $this->manager->supprimerSauvegarde('ADRESSE_STRUCTURE_SAVE');
    }



    protected function decoupageNumVoie(?string $numVoie): array
    {
        $num   = $numVoie;
        $compl = null;

        $compls = [
            'QUINQUIES' => 5,
            'QUIN'      => 5,
            'quin'      => 5,
            'Quinquies' => 5,
            'quinquies' => 5,

            'QUATER' => 4,
            'QUA'    => 4,
            'qua'    => 4,
            'Quater' => 4,
            'quater' => 4,

            'BIS' => 2,
            'Bis' => 2,
            'bis' => 2,

            'TER' => 3,
            'Ter' => 3,
            'ter' => 3,

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

