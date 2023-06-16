CREATE OR REPLACE FORCE VIEW V_CONTRAT_MAIN AS
WITH hs AS (
  SELECT contrat_id, SUM(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
),


la AS(
        SELECT
                contrat_id,
                LISTAGG( libelle, ',')  WITHIN GROUP (ORDER BY libelle)                                                     autre_libelles
        FROM
        (
            SELECT DISTINCT
                c.contrat_id,
                ti.libelle                                                                                                  libelle
            FROM
                contrat c
                JOIN volume_horaire vh ON c.id = vh.contrat_id
                JOIN type_intervention ti ON ti.id = vh.type_intervention_id
                LEFT JOIN                      hs ON hs.contrat_id = c.id
            WHERE
                ti.code NOT IN ('CM','TD','TP')
        )
        GROUP BY contrat_id
    )


SELECT ct.annee_id,
       ct.structure_id,
       ct.intervenant_id,
       ct.formule_resultat_id,
       ct.id                                                                                                                contrat_id,
       ct."composante",
       ct."annee",
       ct."nom",
       ct."prenom",
       ct."civilite",
       ct."e",
       ct."dateNaissance",
       ct."adresse",
       ct."numInsee",
       ct."statut",
       ct."totalHETD",
       ct."tauxHoraireValeur",
       ct."tauxHoraireDate",
       ct."tauxId",
       ct."dateSignature",
       ct."modifieComplete",
       CASE WHEN ct.est_contrat = 1 THEN 1 ELSE NULL END                                                                    "contrat1",
       CASE WHEN ct.est_contrat = 1 THEN NULL ELSE 1 END                                                                    "avenant1",
       CASE WHEN ct.est_contrat = 1 THEN '3' ELSE '2' END                                                                   "n",
       to_char(sysdate, 'dd/mm/YYYY - hh24:mi:ss')                                                                          "horodatage",
       'Exemplaire à conserver'                                                                                             "exemplaire1",
       'Exemplaire à retourner' || ct."exemplaire2"                                                                         "exemplaire2",
       ct."serviceTotal",
       ct."legendeAutresHeures",
       ct."enteteAutresHeures",
       ct."missionNom",
       ct."tauxNom",
       ct."debutValidite",
       ct."finValidite",
       ct."pays_nationalite",
       ct."date_creation",
       ct."date_contrat_lie",
       CASE ct.est_contrat
           WHEN 1 THEN -- contrat
               'Contrat de travail'
           ELSE
                   'Avenant au contrat de travail initial modifiant le volume horaire initial'
                   || ' de recrutement en qualité'
           END                                                                                                              "titre",
       CASE
           WHEN ct.est_atv = 1 THEN
               'd''agent temporaire vacataire'
           ELSE
               'de chargé' || ct."e" || ' d''enseignement vacataire'
           END                                                                                                              "qualite",

       CASE
           WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
           WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
           WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
           WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
           END                                                                                                              "titreCourt"
FROM (  SELECT c.*,
             i.annee_id                                                                                                                                    annee_id,
             fr.id                                                                                                                                         formule_resultat_id,
             s.libelle_court                                                                                                                               "composante",
             a.libelle                                                                                                                                     "annee",
             COALESCE(d.nom_usuel, i.nom_usuel)                                                                                                            "nom",
             COALESCE(d.prenom, i.prenom)                                                                                                                  "prenom",
             civ.libelle_court                                                                                                                             "civilite",
             CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                                                                 "e",
             to_char(COALESCE(d.date_naissance, i.date_naissance), 'dd/mm/YYYY')                                                                           "dateNaissance",
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
                 )                                                                                                                                         "adresse",
             COALESCE(d.numero_insee, i.numero_insee)                                                                                                      "numInsee",
             si.libelle                                                                                                                                    "statut",
             REPLACE(ltrim(to_char(COALESCE(c.total_hetd, fr.total, 0), '999999.00')), '.', ',')                                                           "totalHETD",
             REPLACE(ltrim(to_char(COALESCE(OSE_PAIEMENT.get_taux_horaire(COALESCE(trm.id, trs.id,tr.id), a.date_debut), 0), '999999.00')), '.', ',')      "tauxHoraireValeur",
             COALESCE(to_char(OSE_PAIEMENT.get_taux_horaire_date(COALESCE(trm.id, trs.id,tr.id), a.date_debut), 'dd/mm/YYYY'), 'TAUX INTROUVABLE')         "tauxHoraireDate",
             COALESCE(trm.id, trs.id,tr.id)                                                                                                                "tauxId",
             COALESCE(trm.libelle, trs.libelle,tr.libelle)                                                                                                 "tauxNom",
             to_char(COALESCE(v.histo_creation, a.date_debut), 'dd/mm/YYYY')                                                                               "dateSignature",
             CASE
                 WHEN c.structure_id <> COALESCE(cp.structure_id, 0) THEN 'modifié'
                 ELSE 'complété' END                                                                                                                       "modifieComplete",
             CASE
                 WHEN s.aff_adresse_contrat = 1 THEN
                         ' signé à l''adresse suivante :' || chr(13) || chr(10) ||
                         s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
                                                                     s.adresse_precisions, s.adresse_lieu_dit,
                                                                     s.adresse_numero, s.adresse_numero_compl_id,
                                                                     s.adresse_voirie_id, s.adresse_voie,
                                                                     s.adresse_code_postal, s.adresse_commune,
                                                                     s.adresse_pays_id
                                                                 ), chr(13), ' - ')
                 ELSE '' END                                                                                                                                "exemplaire2",
             REPLACE(ltrim(to_char(COALESCE(hs."serviceTotal", 0), '999999.00')), '.', ',')                                                                 "serviceTotal",
             CASE
                 WHEN la.autre_libelles IS NOT NULL
                     THEN '*Dont type(s) intervention(s) : ' || la.autre_libelles END                                                                       "legendeAutresHeures",
             CASE
                 WHEN la.autre_libelles IS NOT NULL THEN 'Autres heures*'
                 ELSE 'Autres heures' END                                                                                                                   "enteteAutresHeures",
             CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                                                                               est_contrat,
             CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                                                                       est_projet,
             CASE WHEN LOWER(si.codes_corresp_2) = 'oui' THEN 1 ELSE 0 END                                                                                  est_atv,
             tm.libelle                                                                                                                                     "missionNom",
             to_char(c.debut_validite, 'dd/mm/YYYY')                                                                                                        "debutValidite",
             to_char(c.fin_validite, 'dd/mm/YYYY')                                                                                                          "finValidite",
             p.libelle                                                                                                                                      "pays_nationalite",
             COALESCE(v.histo_creation,c.histo_creation)                                                                                                    "date_creation",
             cp.date_retour_signe                                                                                                                           "date_contrat_lie"
        FROM
            contrat                         c
            JOIN type_contrat               tc ON tc.id = c.type_contrat_id
            JOIN intervenant                i ON i.id = c.intervenant_id
            JOIN annee                      a ON a.id = i.annee_id
            JOIN statut                     si ON si.id = i.statut_id
            LEFT JOIN taux_remu             trs ON si.taux_remu_id = trs.id
            JOIN STRUCTURE                  s ON s.id = c.structure_id
            LEFT JOIN intervenant_dossier   d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
            LEFT JOIN pays                  p ON d.pays_nationalite_id = p.id
            JOIN civilite                   civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
            LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
            JOIN type_volume_horaire        tvh ON tvh.code = 'PREVU'
            JOIN etat_volume_horaire        evh ON evh.code = 'valide'
            LEFT JOIN formule_resultat      fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
            JOIN parametre                  p ON p.nom = 'taux-remu'
            LEFT JOIN taux_remu             tr ON tr.id = p.valeur
            LEFT JOIN                       hs ON hs.contrat_id = c.id
            LEFT JOIN                       la ON la.contrat_id = c.id
            LEFT JOIN contrat               cp ON cp.id = c.contrat_id
            LEFT JOIN mission               m ON c.mission_id = m.id
            LEFT JOIN type_mission          tm ON m.type_mission_id = tm.id
            LEFT JOIN taux_remu             trm ON tm.taux_remu_id = trm.id
        WHERE
            c.histo_destruction IS NULL
) ct