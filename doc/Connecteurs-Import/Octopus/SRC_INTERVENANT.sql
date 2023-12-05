CREATE OR REPLACE FORCE VIEW SRC_INTERVENANT AS
SELECT
  CASE WHEN action = 'insert' THEN NULL ELSE intervenant_id END id,
  annee_id,
  code,
  code_rh,
 CASE WHEN sync_utilisateur_code = 1 THEN COALESCE(s_utilisateur_code,i_utilisateur_code) ELSE i_utilisateur_code END     utilisateur_code,
 CASE WHEN mission_structure_id IS NOT NULL THEN mission_structure_id
	  WHEN annee_id < current_annee_id THEN intervenant_structure_id ELSE structure_id END structure_id,
CASE
    WHEN action = 'insert' OR intervenant_histo = 1 THEN statut_source_id
    WHEN (action = 'update-no-statut' OR sync_statut = 0 OR annee_id < current_annee_id) AND statut_intervenant_id IS NOT NULL THEN statut_intervenant_id
    ELSE statut_source_id
  END                                                                                                                      statut_id,
  CASE WHEN annee_id < current_annee_id AND intervenant_id IS NOT NULL THEN intervenant_grade_id ELSE grade_id END         grade_id,
  discipline_id,
  civilite_id,
  nom_usuel,
  prenom,
  date_naissance,
  nom_patronymique,
  commune_naissance,
  pays_naissance_id,
  departement_naissance_id,
  pays_nationalite_id,
  tel_pro,
  tel_perso,
  email_pro,
  email_perso,
  adresse_precisions,
  adresse_numero,
  adresse_numero_compl_id,
  adresse_voirie_id,
  adresse_voie,
  adresse_lieu_dit,
  adresse_code_postal,
  adresse_commune,
  adresse_pays_id,
  numero_insee,
  numero_insee_provisoire,
  --numero_pec,
  -- Pour synchroniser les coord. bancaires uniquement sur l'année n, il faut décommenter les 3 lignes ci-dessous et commenter les trois lignes d'après
  --CASE WHEN annee_id < current_annee_id AND intervenant_id IS NOT NULL THEN i_iban          ELSE s_iban          END iban,
  --CASE WHEN annee_id < current_annee_id AND intervenant_id IS NOT NULL THEN i_bic           ELSE s_bic           END bic,
  --CASE WHEN annee_id < current_annee_id AND intervenant_id IS NOT NULL THEN i_rib_hors_sepa ELSE s_rib_hors_sepa END rib_hors_sepa,
  s_iban          iban,
  s_bic           bic,
 -- s_rib_hors_sepa rib_hors_sepa,
  --autre_1,
  --autre_2,
  --autre_3,
  --autre_4,
  --autre_5,
  affectation_fin,
  employeur_id,
  CASE WHEN statuts_identiques = 0 AND i_source_id IS NOT NULL AND action <> 'insert' THEN i_source_id ELSE s_source_id END source_id,
  source_code,
  CASE WHEN statuts_identiques = 0 AND intervenant_local = 1 AND action <> 'insert' THEN i_validite_debut ELSE s_validite_debut END validite_debut,
  CASE WHEN statuts_identiques = 0 AND intervenant_local = 1 AND action <> 'insert' THEN i_validite_fin ELSE s_validite_fin END validite_fin

FROM (
  SELECT
    t.*,
    CASE

      -- Cas 1 : Si on est sur un statut multiple avec un non-autorisé et un autre statut, alors le non autorisé est supprimé
      WHEN statut_source_nautorise = 1 AND nb_sources > 1 THEN 'drop'


      -- Cas 2 : Si on est sur les mêmes fiches, alors on synchronise tout le temps
      WHEN statuts_identiques = 1 THEN CASE
        -- Si c'est un intervenant qui a été historisé et qu'une nouvelle fiche a été créée plus tard
        WHEN intervenant_histo = 1 AND nb_sources = 1 AND nb_intervenants = 2 AND nb_statuts_egaux = 2 THEN 'drop'

        -- Sinon on met à jour
        ELSE 'update'
      END


      -- Cas 3 : On ne restaure pas les locaux historisés s'il ne sont pas dans la source
      WHEN statuts_identiques = 0 AND intervenant_histo = 1 AND intervenant_local = 1 THEN 'drop'


      -- Cas 4 : Nouvelle insertion
      WHEN nb_sources = 1 AND nb_intervenants = 0 THEN 'insert'


      -- Cas 5 : Quand les statuts matchent à 100%, il faut supprimer les faux positifs
      WHEN nb_sources = nb_intervenants AND nb_sources = nb_statuts_egaux THEN 'drop'


      -- Cas 6 : Quand il y a différence de statut alors que c'est 1 <=> 1
      WHEN nb_sources = 1 AND nb_intervenants = 1 AND statuts_identiques = 0 THEN CASE
        -- Si c'est un intervenant local qui avait été créé, on n'y touche pas et on en recrée un nouveau
        WHEN intervenant_local = 1 THEN 'insert'

        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        -- Pour les fiches synchronisées, s'il n'y a aucune donnée sur la fiche, on change le statut tout simplement
        WHEN intervenant_local = 0 AND sync_statut = 1 AND intervenant_donnees = 0 THEN 'update'

        -- Si l'intervenant est synchro et que les types de statut sont identiques, alors on modifie le statut si nécessaire
        WHEN intervenant_local = 0 AND types_identiques = 1 THEN 'update'

        -- Sinon on insère un nouveau statut
        ELSE 'update'
      END


      -- Cas 7 : Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut
      WHEN nb_sources = 1 AND nb_intervenants > 1 AND nb_statuts_egaux = 1 THEN CASE

        -- Si la fiche est vide et vient du SI alors on supprime
        WHEN intervenant_local = 0 AND intervenant_donnees = 0 THEN 'drop'

        -- Si l'intervenant est historisé alors on supprime, ce sera l'autre fiche qui l'emportera
        WHEN intervenant_histo = 1 THEN 'drop'

        -- Pour le reste on met à jour tout de même
        ELSE 'update-no-statut'
      END


      -- Cas 8 : Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut
      WHEN nb_sources = 1 AND nb_intervenants > 1 AND nb_statuts_egaux = 0 THEN CASE
        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        -- Si on a des données dans la fiche
        WHEN intervenant_donnees = 1 THEN 'update-no-statut'

        -- Si c'est un intervenant local
        WHEN intervenant_local = 1 THEN 'update-no-statut'

        -- sinon on droppe
        ELSE 'drop'
      END


      -- Cas 9 : Quand il y a plusieurs sources et aucun intervenant
      WHEN nb_sources > 1 AND nb_intervenants = 0 THEN CASE

        -- Si un des statuts est NON AUTORISE
        WHEN statut_source_nautorise = 1 THEN 'drop'

        -- Si on est sur un statut "Autres" et qu'il y a un autre statut du même type
        WHEN types_identiques = 1 AND statut_source_autre = 1 THEN 'drop'

        -- sinon on laisse passer
        ELSE 'insert'

      END


      -- Cas 10 : Quand il y a plusieurs sources pour un seul intervenant et qu'une au moins matche
      WHEN nb_sources > 1 AND nb_intervenants = 1 AND nb_statuts_egaux = 1 THEN CASE

        -- Si le nouveau statut est de type différent, alors on ajoute une nouvelle fiche
        WHEN types_identiques = 0 THEN 'insert'

        -- Si on a AUTRE+ NA en source et NA en intervenant => on transforme
        WHEN nb_statut_source_nautorise > 0 AND statut_intervenant_nautorise = 1 THEN 'update'

        -- Sinon, on ne prend pas en compte pour éviter tout problème
        ELSE 'drop'

      END


      -- Cas 11 : Quand il y a plusieurs sources pour un seul intervenant et qu'aucun ne matche
      WHEN nb_sources > 1 AND nb_intervenants = 1 AND nb_statuts_egaux = 0 THEN CASE
        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        -- Si le nouveau statut est de type différent, alors on ajoute une nouvelle fiche
        WHEN types_identiques = 0 THEN 'insert'

        -- Sinon, on ne prend pas en compte pour éviter tout problème
        ELSE 'drop'

      END


      -- Cas 12 : Quand il y a 2 sources et 2 intervenants et qu'un seul matche
      WHEN nb_sources = 2 AND nb_intervenants = 2 THEN CASE

        -- Autres fiches de même type
        WHEN statuts_identiques = 0 AND statut_source_id <> statuts_egaux_id AND statut_intervenant_id <> statuts_egaux_id AND types_identiques = 1 THEN 'update'

        -- Autres fiches de type différent
        WHEN statuts_identiques = 0 AND statut_source_id <> statuts_egaux_id AND statut_intervenant_id <> statuts_egaux_id AND types_identiques = 0 THEN 'insert'

        ELSE 'drop'
      END


      -- Cas 13 : Pour le reste
      ELSE CASE

        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        ELSE 'drop'
      END

    END action
  FROM (
    SELECT
      -- Données
      i.id                                                                                   intervenant_id,
      a.id                                                                                   annee_id,
      current_annee.id                                                                       current_annee_id,
      s.code                                                                                 code,
      s.code_rh                                                                              code_rh,
      i.utilisateur_code                                                                     i_utilisateur_code,
      s.utilisateur_code                                                                     s_utilisateur_code,
      CASE WHEN i.sync_structure = 0 THEN i.structure_id ELSE str.id END                     structure_id,
      i.structure_id                                                                         intervenant_structure_id,
      mi.structure_id																		 mission_structure_id,
      ssi.id                                                                                 statut_source_id,
      i.statut_id                                                                            statut_intervenant_id,
      g.id                                                                                   grade_id,
      i.grade_id                                                                             intervenant_grade_id,
      d.id                                                                                   discipline_id,
      c.id                                                                                   civilite_id,
      s.nom_usuel                                                                            nom_usuel,
      s.prenom                                                                               prenom,
      s.date_naissance                                                                       date_naissance,
      s.nom_patronymique                                                                     nom_patronymique,
      s.commune_naissance                                                                    commune_naissance,
      pnaiss.id                                                                              pays_naissance_id,
      dep.id                                                                                 departement_naissance_id,
      pnat.id                                                                                pays_nationalite_id,
      s.tel_pro                                                                              tel_pro,
      s.tel_perso                                                                            tel_perso,
      s.email_pro                                                                            email_pro,
      s.email_perso                                                                          email_perso,
      s.adresse_precisions                                                                   adresse_precisions,
      s.adresse_numero                                                                       adresse_numero,
      anc.id                                                                                 adresse_numero_compl_id,
      v.id                                                                                   adresse_voirie_id,
      s.adresse_voie                                                                         adresse_voie,
      s.adresse_lieu_dit                                                                     adresse_lieu_dit,
      s.adresse_code_postal                                                                  adresse_code_postal,
      s.adresse_commune                                                                      adresse_commune,
      padr.id                                                                                adresse_pays_id,
      s.numero_insee                                                                         numero_insee,
      COALESCE(s.numero_insee_provisoire,i.numero_insee_provisoire,0)                        numero_insee_provisoire,
      --CASE WHEN i.sync_pec = 0 THEN i.numero_pec ELSE s.numero_pec END                       nunmero_pec,
      s.iban                                                                                 s_iban,
      s.bic                                                                                  s_bic,
      s.rib_hors_sepa                                                                        s_rib_hors_sepa,
      i.iban                                                                                 i_iban,
      i.bic                                                                                  i_bic,
      i.rib_hors_sepa                                                                        i_rib_hors_sepa,
      s.autre_1                                                                              autre_1,
      s.autre_2                                                                              autre_2,
      s.autre_3                                                                              autre_3,
      s.autre_4                                                                              autre_4,
      s.autre_5                                                                              autre_5,
      s.affectation_fin                                                                      affectation_fin,
      empl.id                                                                                employeur_id,
      isrc.id                                                                                i_source_id,
      ssrc.id                                                                                s_source_id,
      s.code                                                                                 source_code,
      i.validite_debut                                                                       i_validite_debut,
      s.validite_debut                                                                       s_validite_debut,
      i.validite_fin                                                                         i_validite_fin,
      s.validite_fin                                                                         s_validite_fin,

      -- Variables calculées
      CASE WHEN ssi.code = 'AUTRES' THEN 1 ELSE 0 END                                        statut_source_autre,
      CASE WHEN isi.code = 'AUTRES' THEN 1 ELSE 0 END                                        statut_intervenant_autre,
      CASE WHEN ssi.code = 'NON_AUTORISE' THEN 1 ELSE 0 END                                  statut_source_nautorise,
      CASE WHEN isi.code = 'NON_AUTORISE' THEN 1 ELSE 0 END                                  statut_intervenant_nautorise,
      CASE WHEN ssi.id = isi.id THEN 1 ELSE 0 END                                            statuts_identiques,
      CASE WHEN ssi.type_intervenant_id = isi.type_intervenant_id THEN 1 ELSE 0 END          types_identiques,
      COALESCE(i.sync_statut,1)                                                              sync_statut,
      COALESCE(i.sync_utilisateur_code,1)                                                    sync_utilisateur_code,
--      COALESCE(i.sync_pec,1)                                                                 sync_pec,
      CASE WHEN COALESCE(isrc.importable,1) = 1 THEN 0 ELSE 1 END                            intervenant_local,
      CASE WHEN idata.intervenant_id IS NULL THEN 0 ELSE 1 END                               intervenant_donnees,
      CASE WHEN i.histo_destruction IS NULL THEN 0 ELSE 1 END                                intervenant_histo,
      COUNT(DISTINCT ssi.id) OVER (PARTITION BY s.code, a.id)                                nb_sources,
      COUNT(DISTINCT i.id) OVER (PARTITION BY i.code, i.annee_id)                            nb_intervenants,
      SUM(CASE WHEN ssi.id = i.statut_id THEN 1 ELSE 0 END) OVER (PARTITION BY s.code, a.id) nb_statuts_egaux,
      COUNT(CASE WHEN ssi.code = 'AUTRES' THEN 1 ELSE 0 END) OVER (PARTITION BY s.code, a.id) nb_statut_source_autre,
      COUNT(CASE WHEN ssi.code = 'NON_AUTORISE' THEN 1 ELSE 0 END) OVER (PARTITION BY s.code, a.id) nb_statut_source_nautorise,
      MAX(CASE WHEN ssi.id = i.statut_id THEN ssi.id ELSE 0 END) OVER (PARTITION BY s.code, a.id) statuts_egaux_id
    FROM
                mv_intervenant          s
           JOIN (SELECT unicaen_import.get_current_annee id FROM dual) current_annee ON 1=1
           JOIN annee                   a ON a.id >= current_annee.id - 1 AND a.active = 1 AND COALESCE(s.validite_debut,a.date_fin) <= a.date_fin AND COALESCE(s.validite_fin,a.date_debut) >= a.date_debut
           JOIN statut                ssi ON ssi.code           = s.z_statut_id and ssi.annee_id = a.id
           JOIN source               ssrc ON ssrc.code          = s.z_source_id
      LEFT JOIN intervenant             i ON i.code             = s.code AND i.annee_id = a.id
      LEFT JOIN source               isrc ON isrc.id            = i.source_id
      LEFT JOIN statut                isi ON isi.id             = i.statut_id
      LEFT JOIN structure             str ON str.source_code    = s.z_structure_id
      LEFT JOIN grade                   g ON g.source_code      = s.z_grade_id
      LEFT JOIN discipline              d ON d.source_code      = s.z_discipline_id
      LEFT JOIN civilite                c ON c.libelle_court    = s.z_civilite_id
      LEFT JOIN pays               pnaiss ON pnaiss.source_code = s.z_pays_naissance_id
      LEFT JOIN departement           dep ON dep.source_code    = s.z_departement_naissance_id
      LEFT JOIN pays                 pnat ON pnat.source_code   = s.z_pays_nationalite_id
      LEFT JOIN adresse_numero_compl  anc ON anc.code           = s.z_adresse_numero_compl_id
      LEFT JOIN voirie                  v ON v.source_code      = s.z_adresse_voirie_id
      LEFT JOIN pays                 padr ON padr.source_code   = s.z_adresse_pays_id
      LEFT JOIN employeur            empl ON empl.source_code   = s.z_employeur_id
      LEFT JOIN (
            SELECT
                m.intervenant_id    intervenant_id,
                m.structure_id      structure_id
            FROM mission m
            JOIN validation_mission vm ON vm.mission_id = m.id
            JOIN validation v2 ON v2.id = vm.validation_id AND v2.histo_destruction IS NULL
            WHERE m.histo_destruction IS NULL
            GROUP BY m.intervenant_id, m.structure_id
            HAVING COUNT(DISTINCT m.structure_id) = 1
          ) mi ON mi.intervenant_id = i.id
      LEFT JOIN (
        SELECT DISTINCT intervenant_id
        FROM (      select intervenant_id from AGREMENT where histo_destruction is null
          union all select intervenant_id from CONTRAT where histo_destruction is null
          union all select intervenant_id from INTERVENANT_DOSSIER where histo_destruction is null
          union all select intervenant_id from MODIFICATION_SERVICE_DU where histo_destruction is null
          union all select intervenant_id from PIECE_JOINTE where histo_destruction is null
          union all select intervenant_id from SERVICE where histo_destruction is null
          union all select intervenant_id from SERVICE_REFERENTIEL where histo_destruction is null
          union all select intervenant_id from VALIDATION where histo_destruction is null
        ) cc
      ) idata ON idata.intervenant_id = i.id
  ) t
) t
WHERE
  action <> 'drop'
