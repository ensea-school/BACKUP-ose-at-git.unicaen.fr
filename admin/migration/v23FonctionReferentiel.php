<?php


use Unicaen\BddAdmin\Bdd;

class v23FonctionReferentiel extends AbstractMigration
{

    public function description (): string
    {
        return "Migration OSE 22 vers OSE 23";
    }



    public function utile (): bool
    {
        return $this->manager->hasNewColumn('FONCTION_REFERENTIEL', 'ANNEE_ID');
    }



    public function before ()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->console();

        //Faire en sorte que les traitements ne soit pas fait sur l'utilisateur oseappli n'existe pas
        $oa = OseAdmin::instance();
        $oa->getOseAppliId();

        $this->traitementContrainteTriggerIndex($bdd, $c);

        $this->traitementFonctionReferentielInsertion($c, $bdd);

        $this->traitementParentIdFonctionReferentiel($c, $bdd);

        $this->traitementServiceReferentiel($c, $bdd);
        $this->traitementPlafondReferentiel($c, $bdd);

        //supprimer les anciennes fonction qui n'ont pas d'année
        $c->msg('Suppression des anciennes fonctions');
        $bdd->exec('DELETE FROM TBL_REFERENTIEL');
        $bdd->exec('DELETE FROM TBL_PLAFOND_REFERENTIEL');
        $bdd->exec('DELETE FROM FONCTION_REFERENTIEL WHERE annee_id IS NULL');
        $c->msg('Fin de la migration des fonctions referentiel');
    }



    /**
     * @param Bdd     $bdd
     * @param OseConsole $c
     *
     * @return void
     */
    public function traitementContrainteTriggerIndex (Bdd $bdd, OseConsole $c): void
    {
        $c->msg('Mise en état de la base de données pour pouvoir migrer');
        //Mise en etat de la base de données pour pouvoir migrer
        try {
            $bdd->exec("ALTER TABLE FONCTION_REFERENTIEL ADD(ANNEE_ID NUMBER)");
        } catch (\Exception $e) {
            $c->printDie('impossible d\'ajouter la colonne ANNEE_ID');
        }

        try {
            $bdd->exec("alter table FONCTION_REFERENTIEL modify HISTO_MODIFICATEUR_ID null");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("ALTER TABLE FONCTION_REFERENTIEL DROP CONSTRAINT FONCTION_REFERENTIEL_UN");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("ALTER TABLE FONCTION_REFERENTIEL DROP CONSTRAINT FONCTION_REFERENTIEL_CODE_UN");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("DROP INDEX FONCTION_REFERENTIEL_CODE_UN");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("DROP INDEX FONCTION_REFERENTIEL_UN");
        } catch (\Exception $e) {
        }
        try {
            $bdd->exec("ALTER TABLE SERVICE_REFERENTIEL DROP CONSTRAINT SERVICE_REFERENTIEL_HISTO_CK");
        } catch (\Exception $e) {
        }

        try {
            $bdd->exec("DROP TRIGGER SERVICE_REFERENTIEL_HISTO_CK");
        } catch (\Exception $e) {
        }
    }



    /**
     * @param OseConsole $c
     * @param Bdd        $bdd
     *
     * @return void
     * @throws \Unicaen\BddAdmin\Exception\BddCompileException
     * @throws \Unicaen\BddAdmin\Exception\BddException
     * @throws \Unicaen\BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementFonctionReferentielInsertion (OseConsole $c, Bdd $bdd): void
    {
        $c->msg('Récuperations des fonctions référentiels');
        $resFonctions = $bdd->select("SELECT *
            FROM FONCTION_REFERENTIEL fr");

        $oa = OseAdmin::instance();

        //Annualiser toutes les fonctions existantes
        $countFonction       = count($resFonctions);
        $nombreFonctionFaite = 0;
        $c->msg('Annualisation des ' . $countFonction . ' fonctions référentiels');
        $table = $bdd->getTable('FONCTION_REFERENTIEL');

        $bdd->beginTransaction();
        foreach ($resFonctions as $fonction) {

            $fonction['HISTO_CREATION']        = new DateTime();
            $fonction['HISTO_CREATEUR_ID']     = $oa->getOseAppliId();
            $fonction['HISTO_MODIFICATEUR_ID'] = null;


            for ($a = 2010; $a < 2100; $a++) {


//                if ($a == $this->anneeCourante()) {
//                    $fonction['HISTO_MODIFICATION']    = new DateTime();
//                    $fonction['HISTO_MODIFICATEUR_ID'] = $oa->getOseAppliId();
//                }

                $fonction['ID']       = null;
                $fonction['ANNEE_ID'] = $a;
                $table->insert($fonction);
            }
            $nombreFonctionFaite++;
            if ($nombreFonctionFaite % 10 == 0) {
                $c->msg('Fonctions annualisées : ' . $nombreFonctionFaite . ' sur ' . $countFonction);
            }
        }
        $bdd->commitTransaction();
    }



    /**
     * @param Console $c
     * @param Bdd     $bdd
     *
     * @return void
     * @throws \Unicaen\BddAdmin\Exception\BddCompileException
     * @throws \Unicaen\BddAdmin\Exception\BddException
     * @throws \Unicaen\BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementParentIdFonctionReferentiel (OseConsole $c, Bdd $bdd): void
    {
        $c->begin('Traitements des fonctions référentiels insérés');
        $lignefonctionTraite = 0;

        $sql = 'select fr2.code, fr2.histo_destruction, fr.annee_id, fr.id
                from fonction_referentiel fr
                JOIN fonction_referentiel fr2 ON fr.parent_id = fr2.id
                WHERE fr.annee_id IS NOT NULL';

        $fonctionsRef = $bdd->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);

        while ($fonctionRef = $fonctionsRef->next()) {
            $newFonction = $this->calculNouvelleFonctionId($bdd, $fonctionRef);
            $bdd->exec('UPDATE fonction_referentiel fr
                            SET fr.parent_id =' . $newFonction . '
                            WHERE fr.id =' . $fonctionRef['ID']);
            $lignefonctionTraite++;
            if ($lignefonctionTraite % 1000 == 0) {
                $c->msg($lignefonctionTraite . ' fonctions traités');
            }
        }
        $c->end('Toutes les fonctions référentielles ont été migré');
    }



    /**
     * @param Bdd   $bdd
     * @param array $itemRef
     *
     * @return mixed
     * @throws \Unicaen\BddAdmin\Exception\BddCompileException
     * @throws \Unicaen\BddAdmin\Exception\BddException
     * @throws \Unicaen\BddAdmin\Exception\BddIndexExistsException
     */
    private function calculNouvelleFonctionId (Bdd $bdd, array $itemRef): mixed
    {
        $sql = 'SELECT fr.id 
                FROM FONCTION_REFERENTIEL fr
                WHERE fr.code = \'' . $itemRef['CODE'] . '\'
                AND fr.annee_id = ' . $itemRef['ANNEE_ID'];

        if ($itemRef['HISTO_DESTRUCTION']) {
            $sql .= ' AND fr.histo_destruction = to_timestamp(\'' . $itemRef['HISTO_DESTRUCTION'] . '\', \'yyyy-mm-dd hh24:mi:ss\')';
        } else {
            $sql .= ' AND fr.histo_destruction IS NULL';
        }
        $res = $bdd->select($sql);

        return $res[0]['ID'];
    }



    /**
     * @param OseConsole $c
     * @param Bdd        $bdd
     *
     * @return void
     * @throws \Unicaen\BddAdmin\Exception\BddCompileException
     * @throws \Unicaen\BddAdmin\Exception\BddException
     * @throws \Unicaen\BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementServiceReferentiel (OseConsole $c, Bdd $bdd): void
    {
        $c->msg('Migration des données du service référentiel vers les nouvelles fonctions');
        $ligneServiceTraite = 0;

        $sql = 'select fr.code, fr.histo_destruction, i.annee_id, sr.id
                from service_referentiel sr
                JOIN intervenant i ON sr.intervenant_id = i.id
                JOIN fonction_referentiel fr ON sr.fonction_id = fr.id';

        $servicesRef = $bdd->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);

        while ($serviceRef = $servicesRef->next()) {

            $newFonction = $this->calculNouvelleFonctionId($bdd, $serviceRef);
            $bdd->exec('UPDATE service_referentiel sr
                            SET sr.fonction_id =' . $newFonction . '
                            WHERE sr.id =' . $serviceRef['ID']);
            $ligneServiceTraite++;
            if ($ligneServiceTraite % 1000 == 0) {
                $c->msg($ligneServiceTraite . ' lignes de service traité');
            }
        }
        $c->msg('Toutes les lignes de service referentiel ont été migré');
    }



    /**
     * @param OseConsole $c
     * @param Bdd        $bdd
     *
     * @return void
     * @throws \Unicaen\BddAdmin\Exception\BddCompileException
     * @throws \Unicaen\BddAdmin\Exception\BddException
     * @throws \Unicaen\BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementPlafondReferentiel (OseConsole $c, Bdd $bdd): void
    {
        $c->msg('Migration des données de plafond referentiel vers les nouvelles fonctions');
        $ligneplafondTraite = 0;

        $sql = 'select fr.code, fr.histo_destruction, pr.annee_id, pr.id
                from plafond_referentiel pr
                JOIN fonction_referentiel fr ON pr.fonction_referentiel_id = fr.id';

        $plafondsRef = $bdd->select($sql, [], ['fetch' => Bdd::FETCH_EACH]);

        while ($plafondRef = $plafondsRef->next()) {

            $newFonction = $this->calculNouvelleFonctionId($bdd, $plafondRef);
            $bdd->exec('UPDATE plafond_referentiel pr
                            SET pr.fonction_referentiel_id =' . $newFonction . '
                            WHERE pr.id =' . $plafondRef['ID']);

            $ligneplafondTraite++;
            if ($ligneplafondTraite % 1000 == 0) {
                $c->msg($ligneplafondTraite . ' plafonds traités');
            }
        }
        $c->msg('Tous les plafond referentiel ont été migré');
    }



    private function anneeCourante ()
    {
        $annee = date('Y');
        $mois  = date('m');
        if ($mois > 8) {
            return $annee;
        } else {
            return $annee - 1;
        }
    }
}