CREATE OR REPLACE FORCE VIEW SRC_INTERVENANT AS
SELECT
  CASE WHEN action = 'insert' THEN NULL ELSE intervenant_id END id,
  annee_id,
  code,
  utilisateur_code,
  CASE WHEN annee_id < current_annee_id THEN intervenant_structure_id ELSE structure_id END                                                    structure_id,
  CASE WHEN action = 'update-no-statut' OR sync_statut = 0 OR annee_id < current_annee_id THEN statut_intervenant_id ELSE statut_source_id END statut_id,
  CASE WHEN annee_id < current_annee_id THEN intervenant_grade_id ELSE grade_id END                                                            grade_id,
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
  iban,
  bic,
  rib_hors_sepa,
  autre_1,
  autre_2,
  autre_3,
  autre_4,
  autre_5,
  employeur_id,
  source_id,
  source_code,
  validite_debut,
  validite_fin

FROM (
  SELECT
    t.*,
    CASE

      -- Nouvelle insertion
      WHEN nb_sources = 1 AND nb_intervenants = 0 THEN 'insert'


      -- Quand les statuts matchent à 100%
      WHEN nb_sources = nb_intervenants AND nb_sources = nb_statuts_egaux THEN CASE
        -- Si ça matche alors ça passe
        WHEN statuts_identiques = 1 THEN 'update'
        -- Sinon ça ne compte pas
        ELSE 'drop'
      END


      -- Quand il y a différence de statut alors que c'est 1 <=> 1
      WHEN nb_sources = 1 AND nb_intervenants = 1 AND statuts_identiques = 0 THEN CASE
        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        -- Pour les fiches synchronisées, s'il n'y a aucune donnée sur la fiche, on change le statut tout simplement
        WHEN intervenant_local = 0 AND sync_statut = 1 AND intervenant_donnees = 0 THEN 'update'

        -- Si l'intervenant est synchro et que les types de statut sont identiques, alors on modifie le statut si nécessaire
        WHEN intervenant_local = 0 AND types_identiques = 1 THEN 'update'

        -- Sinon on insère un nouveau statut
        ELSE 'insert'
      END


      -- Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut
      WHEN nb_sources = 1 AND nb_intervenants > 1 AND nb_statuts_egaux = 1 THEN CASE
        --Si on est sur des statuts identiques
        WHEN statuts_identiques = 1 THEN 'update'

        -- Si on a des données dans la fiche
        WHEN intervenant_donnees = 1 THEN 'update-no-statut'

        -- Si c'est un intervenant local
        WHEN intervenant_local = 1 THEN 'update-no-statut'

        -- Sinon on met tout à jour sauf le statut
        -- si il y a des données alors update sans statut
        -- sinon si intervenant sync alors on droppe sinon intervenant local => on sync sans statut

        ELSE 'drop'
      END


      -- Quand il y a une seule source et plusieurs intervenants, et que la source matche sur au moins un statut
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


      -- Quand il y a plusieurs sources pour un seul intervenant et qu'une au moins matche
      WHEN nb_sources > 1 AND nb_intervenants = 1 AND nb_statuts_egaux = 1 THEN CASE
        -- Si on est sur des statuts identiques
        WHEN statuts_identiques = 1 THEN 'update'

        -- Si le nouveau statut est de type différent, alors on ajoute une nouvelle fiche
        WHEN types_identiques = 0 THEN 'insert'

        -- Sinon, on ne prend pas en compte pour éviter tout problème
        ELSE 'drop'

      END


      -- Quand il y a plusieurs sources pour un seul intervenant et qu'aucun ne matche
      WHEN nb_sources > 1 AND nb_intervenants = 1 AND nb_statuts_egaux = 0 THEN CASE
        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        -- Si le nouveau statut est de type différent, alors on ajoute une nouvelle fiche
        WHEN types_identiques = 0 THEN 'insert'

        -- Sinon, on ne prend pas en compte pour éviter tout problème
        ELSE 'drop'

      END


      -- Quand il y a plusieurs sources pour un seul intervenant et qu'aucun ne matche
      WHEN nb_sources > 1 AND nb_intervenants > 1 THEN CASE
        -- Si on est sur des statuts identiques
        WHEN statuts_identiques = 1 THEN 'update'

        -- Cas typique du vacataire qui a renseigné des données personnelles
        WHEN intervenant_local = 0 AND types_identiques = 1 AND statut_source_autre = 1 AND statut_intervenant_autre = 0 AND sync_statut = 0 THEN 'update'

        ELSE 'drop'
      END


    -- intéger la notion d'année : si < annee_import alors on insert que si total nouveau, sinon on ne fait que des updates sans statut et rien d'autre
    -- gestion des non autorisés ??



      -- Quand il y a une seule source et plusieurs intervenants de statuts différents et que l'un d'entre eux est un vacataire ayant saisi des données perso, alors on update
        -- Si le statut matche UPDATE
        -- sinon ??



      -- créer une source locale synchronisable
      -- modifier UnicaenImport pour gérer les changements de sources

      ELSE NULL
    END action
  FROM (
    SELECT
      -- Données
      i.id                                                                                   intervenant_id,
      a.id                                                                                   annee_id,
      current_annee.id                                                                       current_annee_id,
      s.code                                                                                 code,
      s.utilisateur_code                                                                     utilisateur_code,
      CASE WHEN i.sync_structure = 0 THEN i.structure_id ELSE str.id END                     structure_id,
      i.structure_id                                                                         intervenant_structure_id,
      ssi.id                                                                                 statut_source_id,
      i.statut_id                                                                            statut_intervenant_id,
      g.id                                                                                   grade_id,
      i.grade_id                                                                             intervenant_grade_id,
      COALESCE( d.id, d99.id )                                                               discipline_id,
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
      s.iban                                                                                 iban,
      s.bic                                                                                  bic,
      s.rib_hors_sepa                                                                        rib_hors_sepa,
      s.autre_1                                                                              autre_1,
      s.autre_2                                                                              autre_2,
      s.autre_3                                                                              autre_3,
      s.autre_4                                                                              autre_4,
      s.autre_5                                                                              autre_5,
      empl.id                                                                                employeur_id,
      ssrc.id                                                                                source_id,
      s.code                                                                                 source_code,
      s.validite_debut                                                                       validite_debut,
      s.validite_fin                                                                         validite_fin,

      -- Variables calculées
      CASE WHEN ssi.id = sautre.id  THEN 1 ELSE 0 END                                        statut_source_autre,
      CASE WHEN i.statut_id = sautre.id  THEN 1 ELSE 0 END                                   statut_intervenant_autre,
      CASE WHEN ssi.id = isi.id THEN 1 ELSE 0 END                                            statuts_identiques,
      CASE WHEN ssi.type_intervenant_id = isi.type_intervenant_id THEN 1 ELSE 0 END          types_identiques,
      COALESCE(i.sync_statut,1)                                                              sync_statut,
      CASE WHEN COALESCE(isrc.importable,1) = 1 THEN 0 ELSE 1 END                             intervenant_local,
      CASE WHEN idata.tbls IS NULL THEN 0 ELSE 1 END                                         intervenant_donnees,
      /*CASE WHEN (
        SELECT count(*) FROM intervenant rsi
        WHERE rsi.histo_destruction IS NULL
          AND rsi.id <> i.id
          AND rsi.code = i.code AND rsi.annee_id = i.annee_id AND rsi.statut_id = ssi.id
      ) > 0 THEN 1 ELSE 0 END                                                                  statut_deja_utilise,*/
      COUNT(DISTINCT ssi.id) OVER (PARTITION BY s.code, a.id)                                nb_sources,
      COUNT(DISTINCT i.id) OVER (PARTITION BY i.code, i.annee_id)                            nb_intervenants,
      SUM(CASE WHEN ssi.id = i.statut_id THEN 1 ELSE 0 END) OVER (PARTITION BY s.code, a.id) nb_statuts_egaux
    FROM
      mv_intervenant s
      JOIN statut_intervenant sautre ON sautre.code = 'AUTRES'
      JOIN statut_intervenant ssi ON ssi.code = s.z_statut_id
      JOIN (SELECT unicaen_import.get_current_annee id FROM dual) current_annee ON 1=1
      JOIN annee                   a ON a.id >= current_annee.id - 1 AND a.active = 1 AND COALESCE(s.validite_debut,a.date_fin-1) < a.date_fin AND COALESCE(s.validite_fin,a.date_debut+1) > a.date_debut
      JOIN source ssrc            ON ssrc.code = s.z_source_id

      LEFT JOIN intervenant i ON i.histo_destruction IS NULL AND i.code = s.code AND i.annee_id = a.id
      LEFT JOIN statut_intervenant isi ON isi.id = i.statut_id
      LEFT JOIN source isrc ON isrc.id = i.source_id
      LEFT JOIN (
        SELECT intervenant_id, listagg(cc.t, ', ') within group( order by cc.t) tbls
        FROM (      select count(*) || ' AGREMENT'                t, intervenant_id from AGREMENT where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' CONTRAT'                 t, intervenant_id from CONTRAT where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' INTERVENANT_DOSSIER'     t, intervenant_id from INTERVENANT_DOSSIER where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' MODIFICATION_SERVICE_DU' t, intervenant_id from MODIFICATION_SERVICE_DU where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' PIECE_JOINTE'            t, intervenant_id from PIECE_JOINTE where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' SERVICE'                 t, intervenant_id from SERVICE where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' SERVICE_REFERENTIEL'     t, intervenant_id from SERVICE_REFERENTIEL where histo_destruction is null GROUP BY intervenant_id
          union all select count(*) || ' VALIDATION'              t, intervenant_id from VALIDATION where histo_destruction is null GROUP BY intervenant_id
        ) cc
        GROUP BY intervenant_id
      ) idata ON idata.intervenant_id = i.id
      LEFT JOIN structure             str ON str.source_code = s.z_structure_id
      LEFT JOIN                   grade g ON g.source_code = s.z_grade_id
      LEFT JOIN discipline              d ON
        d.histo_destruction IS NULL
        AND 1 = CASE WHEN -- si rien n'a été défini
          COALESCE( s.z_discipline_id_cnu, s.z_discipline_id_sous_cnu, s.z_discipline_id_spe_cnu, s.z_discipline_id_dis2deg ) IS NULL
          AND d.source_code = '00'

        THEN 1 WHEN -- si une CNU ou une spécialité a été définie...

          COALESCE( s.z_discipline_id_cnu, s.z_discipline_id_sous_cnu, s.z_discipline_id_spe_cnu ) IS NOT NULL

        THEN CASE WHEN -- alors on teste par les sections CNU et spécialités

          (
               ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || s.z_discipline_id_cnu || NVL(s.z_discipline_id_sous_cnu,'') || ',%'
            OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || s.z_discipline_id_cnu || NVL(s.z_discipline_id_sous_cnu,'00') || ',%'
          )
          AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'

        THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré

          s.z_discipline_id_dis2deg IS NOT NULL
          AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || s.z_discipline_id_dis2deg || ',%'

        THEN 1 ELSE 0 END END -- fin du test
      LEFT JOIN discipline            d99 ON d99.source_code = '99'
      LEFT JOIN civilite                c ON c.libelle_court = s.z_civilite_id
      LEFT JOIN pays               pnaiss ON pnaiss.source_code = s.z_pays_naissance_id
      LEFT JOIN departement           dep ON dep.source_code = s.z_departement_naissance_id
      LEFT JOIN pays                 pnat ON pnat.source_code = s.z_pays_nationalite_id
      LEFT JOIN adresse_numero_compl  anc ON anc.code = s.z_adresse_numero_compl_id
      LEFT JOIN voirie                  v ON v.source_code = s.z_adresse_voirie_id
      LEFT JOIN pays                 padr ON padr.source_code = s.z_adresse_pays_id
      LEFT JOIN employeur            empl ON empl.source_code = s.z_employeur_id
  ) t
) t
WHERE
  action <> 'drop'
;