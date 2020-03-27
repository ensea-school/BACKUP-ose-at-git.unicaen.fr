CREATE OR REPLACE FORCE VIEW V_CONTRAT_MAIN AS
WITH hs AS (
  SELECT contrat_id, sum(heures) "serviceTotal" FROM V_CONTRAT_SERVICES GROUP BY contrat_id
)
SELECT
  ct.annee_id,
  ct.structure_id,
  ct.intervenant_id,
  ct.formule_resultat_id,
  ct.id contrat_id,

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
  ct."dateSignature",
  ct."modifieComplete",
  CASE WHEN ct.est_contrat=1 THEN 1 ELSE null END "contrat1",
  CASE WHEN ct.est_contrat=1 THEN null ELSE 1 END "avenant1",
  CASE WHEN ct.est_contrat=1 THEN '3' ELSE '2' END "n",
  to_char(SYSDATE, 'dd/mm/YYYY - hh24:mi:ss') "horodatage",
  'Exemplaire à conserver' "exemplaire1",
  'Exemplaire à retourner' || ct."exemplaire2" "exemplaire2",
  ct."serviceTotal",

  CASE ct.est_contrat
    WHEN 1 THEN -- contrat
      'Contrat de travail'
    ELSE
      'Avenant au contrat de travail initial modifiant le volume horaire initial'
      || ' de recrutement en qualité'
  END                                         "titre",
  CASE WHEN ct.est_atv = 1 THEN
    'd''agent temporaire vacataire'
  ELSE
    'de chargé' || ct."e" || ' d''enseignement vacataire'
  END                                         "qualite",

  CASE
    WHEN ct.est_projet = 1 AND ct.est_contrat = 1 THEN 'Projet de contrat'
    WHEN ct.est_projet = 0 AND ct.est_contrat = 1 THEN 'Contrat n°' || ct.id
    WHEN ct.est_projet = 1 AND ct.est_contrat = 0 THEN 'Projet d''avenant'
    WHEN ct.est_projet = 0 AND ct.est_contrat = 0 THEN 'Avenant n°' || ct.contrat_id || '.' || ct.numero_avenant
  END                                         "titreCourt"
FROM
(
  SELECT
    c.*,
    i.annee_id                                                                                    annee_id,
    fr.id                                                                                         formule_resultat_id,
    a.libelle                                                                                     "annee",
    COALESCE(d.nom_usuel,i.nom_usuel)                                                             "nom",
    COALESCE(d.prenom,i.prenom)                                                                   "prenom",
    civ.libelle_court                                                                             "civilite",
    CASE WHEN civ.sexe = 'F' THEN 'e' ELSE '' END                                                 "e",
    to_char(COALESCE(d.date_naissance,i.date_naissance), 'dd/mm/YYYY')                            "dateNaissance",
    COALESCE(d.adresse,ose_divers.formatted_adresse(
        ai.NO_VOIE, ai.NOM_VOIE, ai.BATIMENT, ai.MENTION_COMPLEMENTAIRE, ai.LOCALITE,
        ai.CODE_POSTAL, ai.VILLE, ai.PAYS_LIBELLE))                                               "adresse",
    COALESCE(d.numero_insee,i.numero_insee || ' ' || COALESCE(LPAD(i.numero_insee_cle,2,'0'),'')) "numInsee",
    si.libelle                                                                                    "statut",
    replace(ltrim(to_char(COALESCE(fr.total,0), '999999.00')),'.',',')                            "totalHETD",
    replace(ltrim(to_char(COALESCE(th.valeur,0), '999999.00')),'.',',')                           "tauxHoraireValeur",
    COALESCE(to_char(th.histo_creation, 'dd/mm/YYYY'), 'TAUX INTROUVABLE')                        "tauxHoraireDate",
    to_char(COALESCE(v.histo_creation, c.histo_creation), 'dd/mm/YYYY')                           "dateSignature",
    CASE WHEN c.structure_id <> COALESCE(cp.structure_id,0) THEN 'modifié' ELSE 'complété' END    "modifieComplete",
    CASE WHEN s.aff_adresse_contrat = 1 THEN
      ' signé à l''adresse suivante :' || CHR(13) || CHR(10) ||
      s.libelle_court || ' - ' || REPLACE(ose_divers.formatted_adresse(
        astr.NO_VOIE, astr.NOM_VOIE, null, null, astr.LOCALITE,
        astr.CODE_POSTAL, astr.VILLE, null), CHR(13), ' - ')
    ELSE '' END                                                                                   "exemplaire2",
    replace(ltrim(to_char(COALESCE(hs."serviceTotal",0), '999999.00')),'.',',')                   "serviceTotal",
    CASE WHEN c.contrat_id IS NULL THEN 1 ELSE 0 END                                              est_contrat,
    CASE WHEN v.id IS NULL THEN 1 ELSE 0 END                                                      est_projet,
    si.tem_atv                                                                                    est_atv

  FROM
              contrat               c
         JOIN type_contrat         tc ON tc.id = c.type_contrat_id
         JOIN intervenant           i ON i.id = c.intervenant_id
         JOIN annee                 a ON a.id = i.annee_id
         JOIN statut_intervenant   si ON si.id = i.statut_id
         JOIN structure             s ON s.id = c.structure_id
    LEFT JOIN adresse_structure  astr ON astr.structure_id = s.id AND astr.principale = 1 AND astr.histo_destruction IS NULL
    LEFT JOIN dossier               d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
         JOIN civilite            civ ON civ.id = COALESCE(d.civilite_id,i.civilite_id)
    LEFT JOIN validation            v ON v.id = c.validation_id AND v.histo_destruction IS NULL
    LEFT JOIN adresse_intervenant  ai ON ai.intervenant_id = i.id AND ai.histo_destruction IS NULL

         JOIN type_volume_horaire tvh ON tvh.code = 'PREVU'
         JOIN etat_volume_horaire evh ON evh.code = 'valide'
    LEFT JOIN formule_resultat     fr ON fr.intervenant_id = i.id AND fr.type_volume_horaire_id = tvh.id AND fr.etat_volume_horaire_id = evh.id
    LEFT JOIN taux_horaire_hetd    th ON c.histo_creation BETWEEN th.histo_creation AND COALESCE(th.histo_destruction,SYSDATE)
    LEFT JOIN                      hs ON hs.contrat_id = c.id
    LEFT JOIN contrat              cp ON cp.id = c.contrat_id
  WHERE
    c.histo_destruction IS NULL
) ct