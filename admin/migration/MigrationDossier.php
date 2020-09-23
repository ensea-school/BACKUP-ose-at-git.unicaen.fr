<?php
/*
SI validation du dossier alors on force complétude à 1 car de toute façon il est validé*
SINON on calcule la complétude du dossier en reprennant
 */





class MigrationDossier extends AbstractMigration
{
    protected $contexte = self::CONTEXTE_ALL;



    public function description(): string
    {
        return "Migration des dossiers en V15";
    }



    public function utile(): bool
    {
        return true;
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
        $this->manager->sauvegarderTable('DOSSIER', 'OLD_DOSSIER');
    }



    protected function after()
    {
        $bdd     = $this->manager->getBdd();
        $console = $this->manager->getOseAdmin()->getConsole();

        /*TEMPORAIRE : récupération des ID déjà utilisé dans intervenant_dossier dans mon cas en dev ou la table n'est pas vide*/
        $intervenantForbidden = ['51957'];

        //Get id pays FRANCE
        $sql      = "SELECT id FROM PAYS WHERE libelle = 'FRANCE'";
        $pays     = $bdd->select($sql);
        $france   = current($pays);
        $idFrance = $france['ID'];


        //$console->println("Calcul du tableau de bord agrement");
        //$bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');
        $sql = "
        SELECT
            * 
        FROM
            DOSSIER d
        WHERE
            d.histo_destruction IS NULL
        ";

        $sql = "
        SELECT
            *
        FROM
            DOSSIER d
        JOIN VALIDATION v ON v.intervenant_id = d.intervenant_id
        JOIN TYPE_VALIDATION tv ON tv.id = v.type_validation_id
        WHERE
            d.histo_destruction IS NULL
            AND tv.code = 'DONNEES_PERSO_PAR_COMP'
        ";

        $dossiers = $bdd->select($sql);
        //Nombre d'agréments concernés
        $nbAgrement = count($dossiers);
        $console->println("Nombre de dossier à migrer : " . count($dossiers));
        die;
        $mappingDossierToIntervenantDossier = [
            'CIVILITE_ID'              => 'CIVILITE_ID',
            'COMMUNE_NAISSANCE'        => 'VILLE_NAISSANCE',
            'DATE_NAISSANCE'           => 'DATE_NAISSANCE',
            'DEPARTEMENT_NAISSANCE_ID' => 'DEPT_NAISSANCE_ID',
            'EMAIL_PERSO'              => 'EMAIL_PERSO',
            'EMAIL_PRO'                => 'EMAIL',
            'ID'                       => 'ID',
            'INTERVENANT_ID'           => 'INTERVENANT_ID',
            'NOM_PATRONYMIQUE'         => 'NOM_PATRONYMIQUE',
            'NOM_USUEL'                => 'NOM_USUEL',
            'NUMERO_INSEE'             => 'NUMERO_INSEE',
            'NUMERO_INSEE_PROVISOIRE'  => 'NUMERO_INSEE_EST_PROVISOIRE',
            'PAYS_NAISSANCE_ID'        => 'PAYS_NAISSANCE_ID',
            'PRENOM'                   => 'PRENOM',
            'RIB_HORS_SEPA'            => 'RIB_HORS_SEPA',
            'STATUT_ID'                => 'STATUT_ID',
            'HISTO_CREATEUR_ID'        => 'HISTO_CREATEUR_ID',
            'HISTO_CREATION'           => 'HISTO_CREATION',
            'HISTO_MODIFICATEUR_ID'    => 'HISTO_MODIFICATEUR_ID',
            'HISTO_MODIFICATION'       => 'HISTO_MODIFICATION',
            'HISTO_DESTRUCTEUR_ID'     => 'HISTO_DESTRUCTEUR_ID',
            'HISTO_DESTRUCTION'        => 'HISTO_DESTRUCTION',
            'PAYS_NATIONALITE_ID'      => 'PAYS_NAISSANCE_ID',
            'BIC'                      => '',
            'IBAN'                     => '',
            'ADRESSE_CODE_POSTAL'      => '',
            'ADRESSE_COMMUNE'          => '',
            'ADRESSE_LIEU_DIT'         => '',
            'ADRESSE_NUMERO'           => '',
            'ADRESSE_NUMERO_COMPL_ID'  => '',
            'ADRESSE_PAYS_ID'          => '',
            'ADRESSE_PRECISIONS'       => '',
            'ADRESSE_VOIE'             => '',
            'ADRESSE_VOIRIE_ID'        => '',
        ];

        $newFieldsIntervenantDossier = [

        ];
        $tableIntervenantDossier     = $bdd->getTable('INTERVENANT_DOSSIER');
        $datasIntervenantDossier     = [];
        foreach ($dossiers as $dossier) {
            if (in_array($dossier['INTERVENANT_ID'], $intervenantForbidden)) {
                //TEMPORAIRE : Pour ne pas toucher à certain intervenant que j'utilise actuellement. A supprimer avant de pousser le code
                $console->println("Intervenant à ne pas modifier", $console::COLOR_RED);
                continue;
            }
            $console->println("Migration du dossier ID : " . $dossier['ID'], $console::COLOR_GREEN);
            $intervenantDossier = [];
            //On traite dans un premier ce qu'on a pu mapper entre ancien dossier et nouveau dossier
            foreach ($mappingDossierToIntervenantDossier as $newColumn => $oldColumn) {
                if (!empty($oldColumn)) {
                    $intervenantDossier[$newColumn] = $dossier[$oldColumn];
                }
            }
            //Traitement des nouvelles colonnes intervenant dossier
            //BIC et IBAN
            $rib = $dossier['RIB'];
            if (!empty($rib)) {
                $splitRib = explode('-', $rib);
                if (count($splitRib) > 1) {
                    $intervenantDossier['BIC'] = $splitRib [0];
                }
                $intervenantDossier['IBAN'] = $splitRib[1];
            }
            //On récupére les adresses
            $adresse = $dossier['ADRESSE'];
            if (!empty($adresse)) {
                if (preg_match("'(.*)([0-9]{5})(.*)'s", $adresse, $out)) {
                    $adressePrecisions = $out[1];
                    $adresseCodePostal = $out[2];
                    $commune           = explode(',', $out[3]);
                    $adresseCommune    = (!empty($commune[1])) ? $commune[1] : $out[3];
                } else {
                    $adressePrecisions = $adresse;
                    $adresseCodePostal = null;
                    $adresseCommune    = null;
                }

                $intervenantDossier['ADRESSE_PRECISIONS']  = $adressePrecisions;
                $intervenantDossier['ADRESSE_COMMUNE']     = $adresseCommune;
                $intervenantDossier['ADRESSE_CODE_POSTAL'] = $adresseCodePostal;
                $intervenantDossier['ADRESSE_PAYS_ID']     = $idFrance;
            }
            $datasIntervenantDossier[] = $intervenantDossier;
        }

        $options['delete'] = false;
        $options['update'] = false;
        $tableIntervenantDossier->merge($datasIntervenantDossier, 'ID', $options);
        $console->println("Calcul du tableau de bord TBL_DOSSIER");
        $bdd->exec('BEGIN unicaen_tbl.calculer(\'dossier\'); END;');
        $console->println("Fin de la migration des dossiers V15");

        //$tacrId = (int)$bdd->select("SELECT id FROM type_agrement WHERE code = 'CONSEIL_RESTREINT'")[0]['ID'];
        /*

               $agrements = $bdd->select($sql);
               //Nombre d'agréments concernés
               $nbAgrement = count($agrements);
               if ($nbAgrement > 0) {
                   $bdd->exec("alter trigger AGREMENT_CK disable");
                   $console->println("Il y a $nbAgrement agréments du conseil restreint a corriger");
                   $nbAgrementCorrige = 0;
                   //On commence le traitement de chaque agrément qui pose problème
                   foreach ($agrements as $agrement) {
                       try {
                           $structureId    = false;
                           $nomIntervenant = $agrement['NOM_USUEL'];
                           $intervenantId  = $agrement['INTERVENANT_ID'];
                           $agrementId     = $agrement['AGREMENT_ID'];
                           $console->println("------------------------------------------------------------------");
                           $console->println("Traitement de l'agrement de $nomIntervenant / Agrement_id : $agrementId");
                           $console->println("Recherche d'un structure id dans la table TBL_AGREMENT");
                           //On recherche d'abord dans la table TBL_AGREMENT pour trouver la structure possible
                           $sqlAgrement = "SELECT
                                            ta.structure_id   structure_id,
                                            ta.intervenant_id intervenant_id
                                        FROM
                                           TBL_AGREMENT ta
                                        WHERE
                                        ta.intervenant_id = $intervenantId
                                        AND ta.type_agrement_id = $tacrId
                                        AND ta.agrement_id IS NULL";

                           $agrements = $bdd->select($sqlAgrement);

                           $nbStructuresPossibles = count($agrements);
                           //Cas où une seule structure est trouvée dans la table TBL_AGREMENT
                           if ($nbStructuresPossibles == 1) {
                               $agrement = current($agrements);
                               //Je set la variable $structureId pour l'update
                               $structureId = $agrement['STRUCTURE_ID'];
                               $console->println("Une seule structure trouvée pour cet intervenant / Structure_id : " . $structureId);
                           } else {
                               //Cas où j'ai plusieurs structures possibles.
                               $console->println("Plusieurs structure_id possible pour cet agrément : $nbStructuresPossibles");
                               $console->println("Recherche des structures possibles via les contrats");
                               //Il faut donc remonter au niveau des contrats pour pouvoir essayer de trouver le bon structure_id de l'agrement
                               $sqlContrats = "SELECT
                                               c.structure_id   structure_id,
                                               c.intervenant_id intervenant_id
                                           FROM
                                               CONTRAT c
                                           WHERE
                                               c.intervenant_id = $intervenantId
                                               AND c.histo_destruction IS NULL";

                               $contrats = $bdd->select($sqlContrats);

                               $nbContrats = count($contrats);
                               //Cas où j'ai un contrat unique valide, je peux donc isoler la structure_id pour la mettre à jour dans l'agrément
                               if ($nbContrats == 1) {
                                   $contrat = current($contrats);
                                   //Je set la variable $structureId pour l'update
                                   $structureId = $contrat['STRUCTURE_ID'];
                                   $console->println("Un contrat unique trouvé et validé avec la structure id : " . $structureId);
                               }
                           }
                           //Si j'ai réussi à identifier une structure unique alors je peux mettre à jour la table agrement
                           if ($structureId !== false) {
                               $console->println("Mise à jour de l'agrement id : $agrementId avec le structure id : $structureId", $console::COLOR_LIGHT_GREEN);
                               $sqlUpdate = "UPDATE AGREMENT SET STRUCTURE_ID = $structureId WHERE ID = $agrementId";
                               $nbAgrementCorrige++;
                               $bdd->exec($sqlUpdate);
                           } else {
                               //Sinon je fais un soft delete de l'agrement
                               //on met une date précise pour retrouver facilement
                               $console->println("Suppression de l'agrément id : $agrementId car aucune structure n'a pu être identifiée", $console::COLOR_LIGHT_RED);
                               $agrementsToRecreate [] = $agrement;
                               $sqlDelete              = "UPDATE
                                       AGREMENT
                                     SET
                                       HISTO_DESTRUCTION = TO_DATE('2020 - 01 - 01', 'YYYY - MM - DD'),
                                       HISTO_DESTRUCTEUR_ID = " . $this->manager->getOseAdmin()->getOseAppliId() . "
                                     WHERE ID = $agrementId";
                               $bdd->exec($sqlDelete);
                           }
                       } catch (Exception $e) {
                           $console->println($e->getMessage());
                       }
                   }

                   if (!empty($agrementsToRecreate)) {
                       $console->println("Seulement $nbAgrementCorrige sur $nbAgrement on été corrigés", $console::COLOR_LIGHT_RED);
                       $console->println("Les intervenants suivants nécessitent une intervention manuelle de votre part : il faut saisir à nouveau leurs agréments du conseil restreint : ", $console::COLOR_LIGHT_RED);
                       foreach ($agrementsToRecreate as $value) {
                           $console->println($value['PRENOM'] . " " . $value['NOM_USUEL'] . " (année : " . $value['ANNEE_ID'] . " / id : " . $value['INTERVENANT_ID'] . " / code : " . $value['CODE_INTERVENANT'] . ")", $console::COLOR_LIGHT_RED);
                       }
                   } else {
                       $console->println("Les $nbAgrement agrements restreints sans structure on été corrigés.", $console::COLOR_LIGHT_GREEN);
                   }

                   $bdd->exec("alter trigger AGREMENT_CK enable");

                   //On recalcule les tableaux de bord agrement et workflow pour tout remettre d'équerre
                   $console->println("------------------------------------------------------------------");
                   $console->println("Nouveau calcul du tableau de bord agrement");
                   $bdd->exec('BEGIN unicaen_tbl.calculer(\'agrement\'); END;');

                   $console->println("Nouveau calcul du tableau de bord workflow");
                   $bdd->exec('BEGIN unicaen_tbl.calculer(\'workflow\'); END;');

                   */

        $console->println("Terminé");
    }
}

