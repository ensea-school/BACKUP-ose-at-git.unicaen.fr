<?php





class MigrationESCodeRh extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_PRE;



    public function description(): string
    {
        return "Suppression de la vue métérialisée MV_EXT_SERVICE, qui sera recréée ensuite avec une nouvelle colonne";
    }



    public function utile(): bool
    {
        try {
            $this->manager->getBdd()->exec('SELECT intervenant_code_rh FROM v_export_service WHERE 1=0');

            // colonne trouvée => pas besoin de pêter mv_ext_service
            return false;
        } catch (\Exception $e) {
            // colonne non trouvée => on supprimera la MV_ext_service
            return true;
        }

        return $this->manager->hasNew('table', 'DOSSIER_CHAMP_AUTRE');
    }



    public function action(string $contexte)
    {
        $this->manager->getBdd()->exec('DROP MATERIALIZED VIEW MV_EXT_SERVICE');
    }

}