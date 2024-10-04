<?php


class v24MisesEnPaiement extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des mises en paiement";
    }



    public function utile(): bool
    {
        return !$this->manager->hasColumn('MISE_EN_PAIEMENT', 'SERVICE_ID');
    }



    public function before()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        $this->manager->sauvegarderTable('MISE_EN_PAIEMENT', 'SAVE_MISE_EN_PAIEMENT');
        $bdd->exec('ALTER TABLE MISE_EN_PAIEMENT ADD (SERVICE_ID NUMBER)');
        $bdd->exec('ALTER TABLE MISE_EN_PAIEMENT ADD (SERVICE_REFERENTIEL_ID NUMBER)');

        $c->msg('Injection des ID de SERVICE dans MISE_EN_PAIEMENT...');
        $sql = 'UPDATE mise_en_paiement SET service_id = (SELECT service_id FROM formule_resultat_service WHERE id = formule_res_service_id) WHERE service_id IS NULL AND formule_res_service_id IS NOT NULL';
        $bdd->exec($sql);

        $c->msg('Injection des ID de SERVICE_REFERENTIEL dans MISE_EN_PAIEMENT...');
        $sql = 'UPDATE mise_en_paiement SET service_referentiel_id = (SELECT service_referentiel_id FROM formule_resultat_service_ref WHERE id = formule_res_service_ref_id) WHERE service_referentiel_id IS NULL AND formule_res_service_ref_id IS NOT NULL';
        $bdd->exec($sql);

        $c->msg('Contrôle final');
        $sql = "SELECT ID from mise_en_paiement WHERE mission_id IS NULL AND service_id IS NULL AND service_referentiel_id IS NULL";
        if ($bdd->select($sql)) {
            $c->printDie('Attention : certaines mises en paiement n\'ont pu être reliées à aucun service');
        } else {
            $this->manager->supprimerSauvegarde('SAVE_MISE_EN_PAIEMENT');
            $c->println('Les mises en paiement sont toutes conformes');
        }

        $sqls = [
            'ALTER TABLE TBL_PAIEMENT DROP CONSTRAINT TBL_PAIEMENT_FRSR_FK',
            'ALTER TABLE TBL_PAIEMENT DROP CONSTRAINT TBL_PAIEMENT_FRS_FK',
            'DROP INDEX TBL_PAIEMENT_FRSR_FK',
            'DROP INDEX TBL_PAIEMENT_FRS_FK',
            'ALTER TABLE TBL_PAIEMENT DROP CONSTRAINT TBL_PAIEMENT_UN',
            'DROP INDEX TBL_PAIEMENT_UN',
        ];

        foreach ($sqls as $sql) {
            $bdd->exec($sql);
        }
    }

}