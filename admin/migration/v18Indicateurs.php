<?php





class v18Indicateurs extends AbstractMigration
{

    public function description(): string
    {
        return "Migration des indicateurs de OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'TYPE_INDICATEUR');
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Préparation à la mise à jour des indicateurs');

        $bdd->exec('ALTER TABLE INDICATEUR ADD (TYPE_INDICATEUR_ID NUMBER)');
        $bdd->exec('CREATE TABLE TYPE_INDICATEUR (  
              ID NUMBER NOT NULL ENABLE,
              LIBELLE VARCHAR2(60 CHAR) NOT NULL ENABLE,
              ORDRE NUMBER DEFAULT 1 NOT NULL ENABLE
            )');

        $indicateurs = require $this->manager->getOseAdmin()->getOseDir() . '/data/indicateurs.php';
        foreach ($indicateurs as $libelle => $type) {
            $data = ['ID' => $type['id'], 'LIBELLE' => $libelle];
            $bdd->getTable('TYPE_INDICATEUR')->insert($data);
            foreach ($type['indicateurs'] as $numero => $indicateur) {
                $bdd->getTable('INDICATEUR')->update(['TYPE_INDICATEUR_ID' => $type['id']], ['NUMERO' => $numero]);
            }
        }

        $bdd->exec('DELETE FROM INDICATEUR WHERE TYPE_INDICATEUR_ID IS NULL');

        $c->end('Préparation à la migration des indicateurs terminée');
    }
}