<?php





class IndexesContraintesDoublons extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Préparation des indexes et des contraintes avant la migration vers la V15";
    }



    public function utile(): bool
    {
        return $this->manager->hasOld('index', 'CORPS_SRC_UN')
            && $this->manager->hasOld('index', 'TBL_AGR_INTERVENANT_FK_IDX');
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
        $bdd->uniqueConstraint()->drop('DISCIPLINE_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('AFFECTATION_RECHERCH_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('CHEMIN_PEDAGOGIQUE_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('EFFECTIFS_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('GROUPE_TYPE_FORMATIO_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('TYPE_FORMATION_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('TYPE_MODULATEUR_EP_SOURCE_UN');
        $bdd->uniqueConstraint()->drop('TBL_VALIDATION_ENSEIGNEMENT_UN');
        $bdd->uniqueConstraint()->drop('AFFECTATION_SOURCE_UN');

        $bdd->refConstraint()->drop('DOSSIER_HCFK');
        $bdd->refConstraint()->drop('DOSSIER_HDFK');
        $bdd->refConstraint()->drop('DOSSIER_HMFK');
        $bdd->refConstraint()->drop('DOSSIER_INTERVENANT_FK');

        $bdd->index()->drop('CORPS_SOURCE_UN');
        $bdd->index()->drop('DISCIPLINE_SOURCE_UN');
        $bdd->index()->drop('TBL_NOEUD_ANNEE_IDX');
        $bdd->index()->drop('TBL_NOEUD_ETAPE_IDX');
        $bdd->index()->drop('TBL_NOEUD_GTF_IDX');
        $bdd->index()->drop('TBL_NOEUD_NETAPE_IDX');
        $bdd->index()->drop('TBL_NOEUD_NOEUD_IDX');
        $bdd->index()->drop('TBL_NOEUD_STRUCTURE_IDX');

        $bdd->exec('UPDATE INTERVENANT SET NUMERO_INSEE_PROVISOIRE = 0 WHERE NUMERO_INSEE_PROVISOIRE IS NULL');
        $bdd->exec('DELETE FROM type_intervention_ep WHERE id IN (
          SELECT max(id) 
          FROM type_intervention_ep 
          WHERE histo_destruction IS NOT NULL
          GROUP BY source_code, histo_destruction 
          HAVING count(*) > 1
          )'
        );
    }



    protected function after()
    {

    }
}

