<?php





class v18Plafonds extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration des plafonds de OSE 17 vers OSE 18";
    }



    public function utile(): bool
    {
        return $this->manager->hasNew('table', 'PLAFOND_PERIMETRE');
    }



    public function action(string $contexte)
    {
        if ($contexte == self::CONTEXTE_PRE) {
            $this->before();
        } else {
            $this->after();
        }
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        if (empty($bdd->table()->get('SAVE_V18_STATUT'))) {
            $this->manager->sauvegarderTable('STATUT_INTERVENANT', 'SAVE_V18_STATUT');
            $c->msg('Anciens statuts "STATUT_INTERVENANT" sauvegardés dans "SAVE_V18_STATUT".');
        }

        if (empty($bdd->table()->get('SAVE_V18_STRUCTURE'))) {
            $this->manager->sauvegarderTable('STRUCTURE', 'SAVE_V18_STRUCTURE');
            $c->msg('Anciennes structures "STRUCTURE" sauvegardés dans "SAVE_V18_STRUCTURE".');
        }

        if (empty($bdd->table()->get('SAVE_V18_REFERENTIEL'))) {
            $this->manager->sauvegarderTable('FONCTION_REFERENTIEL', 'SAVE_V18_REFERENTIEL');
            $c->msg('Anciennes fonctions référentielles "FONCTION_REFERENTIEL" sauvegardées dans "SAVE_V18_REFERENTIEL".');
        }

        if (empty($bdd->table()->get('SAVE_V18_PLAFOND_APP'))) {
            $this->manager->sauvegarderTable('PLAFOND_APPLICATION', 'SAVE_V18_PLAFOND_APP');
            $c->msg('Anciens paramétrages des plafonds "PLAFOND_APPLICATION" sauvegardées dans "SAVE_V18_PLAFOND_APP".');
        }

        if (!empty($bdd->table()->get('PLAFOND_APPLICATION'))) {
            $bdd->exec('DROP TABLE PLAFOND_APPLICATION');
            $c->msg('Suppression des anciens paramétrages de plafonds');
        }

        if (empty($bdd->table()->get('SAVE_V18_PLAFOND'))) {
            $this->manager->sauvegarderTable('PLAFOND', 'SAVE_V18_PLAFOND');
            $c->msg('Anciens plafonds "PLAFOND" sauvegardées dans "SAVE_V18_PLAFOND".');
        }

        if (!empty($bdd->table()->get('PLAFOND'))) {
            $bdd->exec('DROP TABLE PLAFOND');
            $c->msg('Suppression des anciens plafonds');
        }
    }



    protected function after()
    {
        
    }



    public function preMigrationIndicateurs()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->getConsole();

        $c->begin('Préparation à la mise à jour des indicateurs');

        $c->end('Préparation à la migration des indicateurs terminée');
    }

}