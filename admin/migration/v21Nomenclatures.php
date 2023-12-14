<?php





class v21Nomenclatures extends AbstractMigration
{

    public function description(): string
    {
        return "Peuplement des nouvelles nomenclatures";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'TAUX_REMU');
    }



    public function after()
    {
        $c   = $this->manager->getOseAdmin()->console();
        $bdd = $this->manager->getBdd();

        $du = $bdd->dataUpdater();

        $du->run('install', 'TAUX_REMU');
        $du->run('install', 'TAUX_REMU_VALEUR');
        $du->run('install', 'JOUR_FERIE');
        $du->run('install', 'TYPE_SERVICE');
        $du->run('install', 'TYPE_MISSION');

        $bdd->exec("update etat_sortie set PDF_TRAITEMENT = replace(PDF_TRAITEMENT, 'Application\Entity\Db\TypeIntervention', 'OffreFormation\Entity\Db\TypeIntervention') where PDF_TRAITEMENT like '%Application\Entity\Db\TypeIntervention%'");
        $bdd->exec("update etat_sortie set csv_traitement = replace(csv_traitement, 'Application\Entity\Db\TypeIntervention', 'OffreFormation\Entity\Db\TypeIntervention') where csv_traitement like '%Application\Entity\Db\TypeIntervention%'");
    }
}