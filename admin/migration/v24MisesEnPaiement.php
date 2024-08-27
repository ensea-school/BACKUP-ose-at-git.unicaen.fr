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
        $this->manager->sauvegarderTable('MISE_EN_PAIEMENT', 'SAVE_MISE_EN_PAIEMENT');
    }



    public function after()
    {
        /** @todo à terminer... */

        'update mise_en_paiement set service_id = (select service_id from formule_resultat_service where id = formule_res_service_id) WHERE service_id IS NULL AND formule_res_service_id IS NOT NULL';

        'update mise_en_paiement set service_referentiel_id = (select service_referentiel_id from formule_resultat_service_ref where id = formule_res_service_ref_id) WHERE service_referentiel_id IS NULL AND formule_res_service_ref_id IS NOT NULL';

        $this->manager->supprimerSauvegarde('SAVE_MISE_EN_PAIEMENT');
    }

}