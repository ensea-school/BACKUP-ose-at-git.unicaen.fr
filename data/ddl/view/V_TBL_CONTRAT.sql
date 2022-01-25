CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
WITH t AS (
  SELECT
    i.annee_id                                                                annee_id,
    i.id                                                                      intervenant_id,
    si.peut_avoir_contrat                                                     peut_avoir_contrat,
    NVL(ep.structure_id, i.structure_id)                                      structure_id,
    CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END edite,
    CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END signe
  FROM
              intervenant                 i

         JOIN statut                     si ON si.id = i.statut_id

         JOIN service                     s ON s.intervenant_id = i.id
                                           AND s.histo_destruction IS NULL

         JOIN type_volume_horaire       tvh ON tvh.code = 'PREVU'

         JOIN volume_horaire             vh ON vh.service_id = s.id
                                           AND vh.histo_destruction IS NULL
                                           AND vh.heures <> 0
                                           AND vh.type_volume_horaire_id = tvh.id
                                           AND vh.motif_non_paiement_id IS NULL

         JOIN v_volume_horaire_etat     vhe ON vhe.volume_horaire_id = vh.id

         JOIN etat_volume_horaire       evh ON evh.id = vhe.etat_volume_horaire_id
                                           AND evh.code IN ('valide', 'contrat-edite', 'contrat-signe')

         JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id

  WHERE
    i.histo_destruction IS NULL
    /*@INTERVENANT_ID=i.id*/
    /*@ANNEE_ID=i.annee_id*/
    AND NOT (si.peut_avoir_contrat = 0 AND evh.code = 'valide')
)
SELECT
  annee_id,
  intervenant_id,
  peut_avoir_contrat,
  structure_id,
  count(*) as nbvh,
  sum(edite) as edite,
  sum(signe) as signe
FROM
  t
GROUP BY
  annee_id,
  intervenant_id,
  peut_avoir_contrat,
  structure_id