CREATE OR REPLACE FORCE VIEW V_CONTRAT_SERVICES AS
SELECT
  t.intervenant_id,
  t.contrat_id,
  t.type_service_id,
  t.structure_id,
  t.element_pedagogique_id,
  t.fonction_referentiel_id,
  t.mission_id,
  t.type_mission_id,

  t.type_service           "typeService",
  t.service_composante     "serviceComposante",
  t.periode                "periode",
  t.service_code           "serviceCode",
  t.service_libelle        "serviceLibelle",
  t.service_heures         "serviceHeures",

  SUM(t.heures)            "heures",
  SUM(t.hetd)              "hetd",

  CASE WHEN SUM(t.cm) = 0 THEN '0' ELSE REPLACE(ltrim(to_char(SUM(t.cm), '999999.00')),'.',',') END "cm",
  CASE WHEN SUM(t.td) = 0 THEN '0' ELSE REPLACE(ltrim(to_char(SUM(t.td), '999999.00')),'.',',') END "td",
  CASE WHEN SUM(t.tp) = 0 THEN '0' ELSE REPLACE(ltrim(to_char(SUM(t.tp), '999999.00')),'.',',') END "tp",
  CASE WHEN SUM(t.autres) = 0 THEN '0' ELSE REPLACE(ltrim(to_char(SUM(t.autres), '999999.00')),'.',',') END "autres"
FROM
  (
  SELECT
    tblc.intervenant_id                                  intervenant_id,
    tblc.contrat_id                                      contrat_id,
    tblc.type_service_id                                 type_service_id,
    str.id                                               structure_id,
    ep.id                                                element_pedagogique_id,
    fr.id                                                fonction_referentiel_id,
    m.id                                                 mission_id,
    tm.id                                                type_mission_id,

    ts.code                                              type_service,
    str.libelle_court                                    service_composante,
    p.libelle_long                                       periode,
    COALESCE(tm.code, fr.code, ep.code)                  service_code,
    COALESCE(tm.libelle, fr.libelle_long, ep.libelle)    service_libelle,

    -- somme par contrat
    SUM(tblc.heures) OVER (PARTITION BY tblc.intervenant_id, tblc.contrat_id, tblc.type_service_id, COALESCE(ep.id,tm.id,fr.id) ,p.libelle_long) service_heures,

    -- heures atomiques
    tblc.heures            heures,
    tblc.hetd              hetd,
    tblc.cm                cm,
    tblc.td                td,
    tblc.tp                tp,
    tblc.autres            autres

  FROM
              tbl_contrat        tblc
         JOIN type_service         ts ON ts.id = tblc.type_service_id
    LEFT JOIN service               s ON s.id = tblc.service_id
    LEFT JOIN element_pedagogique  ep ON ep.id = s.element_pedagogique_id
    LEFT JOIN volume_horaire       vh ON vh.id = tblc.volume_horaire_id
    LEFT JOIN periode               p ON p.id = vh.periode_id

    LEFT JOIN service_referentiel  sr ON sr.id = tblc.service_referentiel_id
    LEFT JOIN fonction_referentiel fr ON fr.id = sr.fonction_id

    LEFT JOIN mission               m ON m.id = tblc.mission_id
    LEFT JOIN type_mission         tm ON tm.id = m.type_mission_id

    LEFT JOIN structure           str ON str.id = COALESCE(m.structure_id, sr.structure_id, ep.structure_id)
  WHERE
    tblc.actif = 1
) t
GROUP BY
  t.intervenant_id,
  t.contrat_id,
  t.type_service_id,
  t.structure_id,
  t.element_pedagogique_id,
  t.fonction_referentiel_id,
  t.mission_id,
  t.type_mission_id,

  t.type_service,
  t.service_composante,
  t.periode,
  t.service_code,
  t.service_libelle,
  t.service_heures