<?php


use Unicaen\BddAdmin\Bdd;

class v23FonctionReferentielCorrectionReport extends AbstractMigration
{

    public function description(): string
    {
        return "Correction des fonctions referentielles mal reporté";
    }



    public function utile(): bool
    {
        $bdd = $this->manager->getBdd();

        $res = $bdd->select('select count(sr.id) C 
                                    from service_referentiel sr
                                    join fonction_referentiel fr ON fr.id = sr.fonction_id  
                                    join intervenant i ON i.id = sr.intervenant_id
                                    WHERE i.annee_id <> fr.annee_id'
        );


        return $res[0]['C'] != 0;
    }



    public function before()
    {
        $bdd = $this->manager->getBdd();
        $c   = $this->manager->getOseAdmin()->console();

        $c->msg('Correction des fonctions référentielles initialisées incorrectement par le bouton réalisé->prévisionnel');
        $bdd->exec("ALTER TRIGGER SERVICE_REFERENTIEL_HISTO_CK DISABLE");
        $resServiceRef = $bdd->select('select sr.id, fr2.id NEW_FONCTION
                                                from service_referentiel sr
                                                join fonction_referentiel fr ON fr.id = sr.fonction_id  
                                                join intervenant i ON i.id = sr.intervenant_id
                                                left join fonction_referentiel fr2 ON fr2.code = fr.code AND fr2.annee_id = i.annee_id
                                                WHERE i.annee_id <> fr.annee_id'
        );


        foreach ($resServiceRef as $serviceRef) {
            $param = ['nf' => $serviceRef['NEW_FONCTION'], 'sr_id' => $serviceRef['ID']];
            $bdd->exec('UPDATE service_referentiel sr
                                SET sr.fonction_id = :nf
                                WHERE sr.id = :sr_id', $param);


        }
        $bdd->exec("ALTER TRIGGER SERVICE_REFERENTIEL_HISTO_CK ENABLE");
        $c->msg('Tous les services référentiels sont désormais bien associé à la fonction sur la bonne année');

    }


}