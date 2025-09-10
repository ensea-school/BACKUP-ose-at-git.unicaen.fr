CREATE OR REPLACE FORCE VIEW V_TBL_DMEP_LIQUIDATION AS
SELECT
  t1.annee_id,
  t1.type_ressource_id,
  t1.structure_id,
  str.ids structure_ids,
  SUM(t1.heures) heures
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
         JOIN service                    s ON s.id = mep.service_id
         JOIN intervenant                i ON i.id = s.intervenant_id
    LEFT JOIN element_pedagogique       ep ON ep.id = s.element_pedagogique_id
  WHERE
    mep.histo_destruction IS NULL
    /*@intervenant_id=i.id*/
    /*@annee_id=i.annee_id*/

  UNION ALL

  SELECT
    i.annee_id,
    cc.type_ressource_id,
    sr.structure_id structure_id,
    heures
  FROM
              mise_en_paiement              mep
         JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
         JOIN service_referentiel            sr ON sr.id = mep.service_referentiel_id
         JOIN intervenant                     i ON i.id = sr.intervenant_id

  WHERE
    mep.histo_destruction IS NULL
    /*@intervenant_id=i.id*/
    /*@annee_id=i.annee_id*/

) t1
JOIN structure str ON str.id = t1.structure_id
GROUP BY
  t1.annee_id, t1.type_ressource_id, t1.structure_id, str.ids