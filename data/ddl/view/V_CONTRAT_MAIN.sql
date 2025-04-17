CREATE OR REPLACE FORCE VIEW V_CONTRAT_MAIN AS
SELECT
  -- identifiants
  a.id                                                                                  annee_id,
  s.id                                                                                  structure_id,
  i.id                                                                                  intervenant_id,
  tc.contrat_id                                                                         contrat_id,


  -- Champs principaux du contrat
  CASE tyc.code
    WHEN 'CONTRAT' THEN
      'Contrat de travail'
    ELSE
      'Avenant au contrat de travail initial modifiant le volume horaire initial de recrutement en qualité'
  END                                                                                   "titre",
  CASE
    WHEN tc.edite = 0 AND tyc.code = 'CONTRAT' THEN 'Projet de contrat'
    WHEN tc.edite = 1 AND tyc.code = 'CONTRAT' THEN 'Contrat n°' || tc.contrat_id
    WHEN tc.edite = 0 AND tyc.code = 'AVENANT' THEN 'Projet d''avenant'
    WHEN tc.edite = 1 AND tyc.code = 'AVENANT' THEN 'Avenant n°' || cp.id || '.' || tc.numero_avenant
  END                                                                                   "titreCourt",
  tc.numero_avenant                                                                     "numeroAvenant",
  to_char(sysdate, 'dd/mm/YYYY - hh24:mi:ss')                                           "horodatage",
  a.libelle                                                                             "annee",
  s.libelle_court                                                                       "composante",
  CASE
    WHEN tc.structure_id <> COALESCE(cp.structure_id, 0) THEN 'modifié'
    ELSE 'complété'
  END                                                                                   "modifieComplete",
  CASE WHEN tyc.code = 'CONTRAT' THEN 1 ELSE NULL END                                   "contrat1",
  CASE WHEN tyc.code = 'CONTRAT' THEN NULL ELSE 1 END                                   "avenant1",
  CASE WHEN tyc.code = 'CONTRAT' THEN '3' ELSE '2' END                                  "n",
  'Exemplaire à conserver'                                                              "exemplaire1",
  'Exemplaire à retourner' || CASE
    WHEN s.aff_adresse_contrat = 1 THEN
       ' signé à l''adresse suivante :' || chr(13) || chr(10)
       || s.libelle_court || ' - '
       || REPLACE(ose_divers.formatted_adresse(
          s.adresse_precisions, s.adresse_lieu_dit,
          s.adresse_numero, s.adresse_numero_compl_id,
          s.adresse_voirie_id, s.adresse_voie,
          s.adresse_code_postal, s.adresse_commune,
          s.adresse_pays_id
        ), chr(13), ' - ')
    ELSE ''
  END                                                                                   "exemplaire2",
  to_char(COALESCE(v.histo_creation, a.date_debut), 'dd/mm/YYYY')                       "dateSignature",
  to_char(tc.date_debut, 'dd/mm/YYYY')                                                  "debutValidite",
  to_char(tc.date_fin, 'dd/mm/YYYY')                                                    "finValidite",
  to_char(cp.fin_validite, 'dd/mm/YYYY')                                                "finValiditeParent",
  to_char(tc.date_creation, 'dd/mm/YYYY')                                               "dateCreation",
  to_char(cp.date_retour_signe, 'dd/mm/YYYY')                                           "dateContratLie",
  CASE
    WHEN tc.autre_libelle IS NOT NULL AND ts.code <> 'MIS'
      THEN '*Dont type(s) intervention(s) : ' || tc.autre_libelle
  END                                                                                   "legendeAutresHeures",
  'Autres heures' || CASE WHEN tc.autre_libelle IS NOT NULL AND ts.code <> 'MIS' THEN '*' ELSE '' END "enteteAutresHeures",
  tc.types_mission_libelles                                                             "typesMission",
  tc.missions_libelles                                                                  "missions",
  CASE WHEN ts.code = 'MIS' THEN tc.autre_libelle ELSE NULL END                         "missionsTypesMissions",


  -- Données concernant l'intervenant
  si.libelle                                                                            "statut",
  CASE
    WHEN LOWER(si.codes_corresp_2) = 'oui' THEN
      'd''agent temporaire vacataire'
    ELSE
      'de chargé' || CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END || ' d''enseignement vacataire'
  END                                                                                   "qualite",
  COALESCE(d.nom_usuel, i.nom_usuel)                                                    "nom",
  COALESCE(d.prenom, i.prenom)                                                          "prenom",
  civ.libelle_court                                                                     "civilite",
  to_char(COALESCE(d.date_naissance, i.date_naissance), 'dd/mm/YYYY')                   "dateNaissance",
  CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                         "e",
  COALESCE(
    ose_divers.formatted_adresse(
      d.adresse_precisions, d.adresse_lieu_dit,
      d.adresse_numero, d.adresse_numero_compl_id, d.adresse_voirie_id, d.adresse_voie,
      d.adresse_code_postal, d.adresse_commune, d.adresse_pays_id
    ),
    ose_divers.formatted_adresse(
      i.adresse_precisions, i.adresse_lieu_dit,
      i.adresse_numero, i.adresse_numero_compl_id, i.adresse_voirie_id, i.adresse_voie,
      i.adresse_code_postal, i.adresse_commune, i.adresse_pays_id
    )
  )                                                                                     "adresse",
  COALESCE(d.numero_insee, i.numero_insee)                                              "numInsee",
  p.libelle                                                                             "paysNationalite",


  -- Données portant sur les heures
  OSE_DIVERS.FORMAT_FLOAT(tc.total_global_hetd)                                         "totalHETD",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_heures / 10)                                         "heuresPeriodeEssai",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_heures / 10)                                         "heuresPrimePrecarite",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_heures)                                              "serviceTotal",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_hetd)                                                "hetdContrat",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_heures * (1+tc.taux_conges_payes))                   "serviceTotalPaye",
  OSE_DIVERS.FORMAT_FLOAT(tc.total_heures_formation)                                    "heuresFormation",


  -- Données RH dec taux de rémunération
  tr.libelle                                                                            "tauxNom",
  OSE_DIVERS.FORMAT_FLOAT(tc.taux_remu_valeur)                                          "tauxHoraireValeur",
  to_char(tc.taux_remu_date,'dd/mm/YYYY')                                               "tauxHoraireDate",
  trm.libelle                                                                           "tauxMajoreNom",
  OSE_DIVERS.FORMAT_FLOAT(tc.taux_remu_majore_valeur)                                   "tauxMajoreHoraireValeur",
  to_char(tc.taux_remu_date, 'dd/mm/YYYY')                                              "tauxMajoreHoraireDate"

FROM
            tbl_contrat        tc
       JOIN type_contrat      tyc ON tyc.id = tc.type_contrat_id -- à garder ou non ? attention au changement de type de contrat pour les projets...
       JOIN annee               a ON a.id = tc.annee_id
       JOIN structure           s ON s.id = tc.structure_id
       JOIN intervenant         i ON i.id = tc.intervenant_id
       JOIN statut             si ON si.id = i.statut_id
       JOIN taux_remu          tr ON tr.id = tc.taux_remu_id
       JOIN taux_remu         trm ON trm.id = tc.taux_remu_majore_id
       JOIN type_service       ts ON ts.id = tc.type_service_id
  LEFT JOIN intervenant_dossier d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
  LEFT JOIN civilite          civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
  LEFT JOIN pays                p ON p.id = COALESCE(d.pays_nationalite_id, i.pays_nationalite_id)
  LEFT JOIN contrat            cp ON cp.id = tc.contrat_parent_id
  LEFT JOIN validation          v ON v.id = tc.validation_id
WHERE
  tc.contrat_id IS NOT NULL
  AND tc.volume_horaire_index = 0