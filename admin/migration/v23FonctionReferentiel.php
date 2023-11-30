<?php


use BddAdmin\Bdd;

class v23FonctionReferentiel extends AbstractMigration
{

    public function description(): string
    {
        return "Migration OSE 22 vers OSE 23";
    }



    public function utile(): bool
    {
        return $this->manager->hasNewColumn('FONCTION_REFERENTIEL', 'ANNEE_ID');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();
        $c->msg('Mise en etat de la base de données pour pouvoir migrer');
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
     * @param Console $c
     *
     * @return void
     */
    public function traitementContrainteTriggerIndex(Bdd $bdd, Console $c): void
    {

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
     * @param Console $c
     * @param Bdd     $bdd
     *
     * @return mixed
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementFonctionReferentielInsertion(Console $c, Bdd $bdd): void
    {
        $c->msg('Récuperations des fonctions référentiels');
        $resFonctions = $bdd->select("SELECT *
            FROM FONCTION_REFERENTIEL fr");

        $oa = OseAdmin::getInstance();

        //Annualiser toute les fonctions existantes
        $countFonction       = count($resFonctions);
        $nombreFonctionFaite = 1;
        $c->msg('Annualisation des ' . $countFonction . ' fonctions référentiels');
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
                $bdd->getTable('FONCTION_REFERENTIEL')->insert($fonction);
            }
            if ($nombreFonctionFaite % 10 == 0) {
                $c->msg('Fonction annualisé : ' . $nombreFonctionFaite . ' sur ' . $countFonction);
            }
            $nombreFonctionFaite++;
        }
        $bdd->commitTransaction();
    }



    /**
     * @param Console $c
     * @param Bdd     $bdd
     *
     * @return void
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementParentIdFonctionReferentiel(Console $c, Bdd $bdd): void
    {
        $c->begin('Traitement des fonctions réferentiels inseré');
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
        $c->end('Toutes les fonction referentiel ont été migré');
    }



    private function calculNouvelleFonctionId(Bdd $bdd, array $itemRef): mixed
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
     * @param Console $c
     * @param Bdd     $bdd
     *
     * @return void
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementServiceReferentiel(Console $c, Bdd $bdd): void
    {
        $c->msg('Migration des données du service referentiel vers les nouvelles fonctions');
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
     * @param Console $c
     * @param Bdd     $bdd
     *
     * @return void
     * @throws \BddAdmin\Exception\BddCompileException
     * @throws \BddAdmin\Exception\BddException
     * @throws \BddAdmin\Exception\BddIndexExistsException
     */
    public function traitementPlafondReferentiel(Console $c, Bdd $bdd): void
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



    private function anneeCourante()
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