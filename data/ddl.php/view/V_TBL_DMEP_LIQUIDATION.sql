CREATE OR REPLACE FORCE VIEW V_TBL_DMEP_LIQUIDATION AS
SELECT
  annee_id,
  type_ressource_id,
  structure_id,
  SUM(heures) heures
FROM
(
  SELECT
    i.annee_id,
    cc.type_ressource_id,
    COALESCE( ep.structure_id, i.structure_id ) structure_id,
    mep.heures
  FROM
              mise_en_paiement         mep
         JOIN centre_cout               cc ON cc.id = mep.centre_cout_id
         JOIN formule_resultat_service frs ON frs.id = mep.formule_res_service_id
         JOIN service                    s ON s.id = frs.service_id
         JOIN intervenant                i ON i.id = s.intervenant_id
    LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  WHERE
    mep.histo_destruction IS NULL

  UNION ALL

  SELECT
    i.annee_id,
    cc.type_ressource_id,
    sr.structure_id structure_id,
    heures
  FROM
              mise_en_paiement              mep
         JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
         JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
         JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
         JOIN intervenant                     i ON i.id = sr.intervenant_id

  WHERE
    mep.histo_destruction IS NULL

) t1
GROUP BY
  annee_id, type_ressource_id, structure_id