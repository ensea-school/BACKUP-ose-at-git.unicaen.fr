CREATE OR REPLACE FORCE VIEW V_TBL_AGREMENT AS
WITH i_s AS (
  SELECT
    fr.intervenant_id,
    ep.structure_id
  FROM
    formule_resultat fr
    JOIN type_volume_horaire  tvh ON tvh.code = 'PREVU' AND tvh.id = fr.type_volume_horaire_id
    JOIN etat_volume_horaire  evh ON evh.code = 'valide' AND evh.id = fr.etat_volume_horaire_id

    JOIN formule_resultat_service frs ON frs.formule_resultat_id = fr.id
    JOIN service s ON s.id = frs.service_id
    JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  WHERE
    frs.total > 0
    /*@INTERVENANT_ID=fr.intervenant_id*/
),
avi AS (
    SELECT
        i.code                code_intervenant,
        i.annee_id            annee_id,
        a.type_agrement_id    type_agrement,
        a.id             agrement_id,
        tas.duree_vie         duree_vie,
        i.annee_id+duree_vie date_validite
    FROM intervenant i
    JOIN type_agrement_statut tas ON tas.statut_intervenant_id = i.statut_id
    JOIN agrement a ON a.intervenant_id = i.id AND tas.type_agrement_id = a.type_agrement_id AND a.histo_destruction IS NULL
)
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE","RANK" FROM (
    SELECT
      i.annee_id                     annee_id,
      CASE
        WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END   annee_agrement,
      tas.type_agrement_id                       type_agrement_id,
      i.id                                       intervenant_id,
      i.code                                     code_intervenant,
      null                                       structure_id,
      tas.obligatoire                            obligatoire,
      NVL(a.id, avi.agrement_id)                 agrement_id,
      tas.duree_vie                              duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id ORDER BY
        CASE
        WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                  ta
      JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                        AND tas.histo_destruction IS NULL

      JOIN intervenant                 i ON i.histo_destruction IS NULL
                                        AND i.statut_id = tas.statut_intervenant_id

      JOIN                           i_s ON i_s.intervenant_id = i.id


      LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                        AND a.intervenant_id = i.id
                                        AND a.histo_destruction IS NULL

      LEFT JOIN                      avi ON i.code = avi.code_intervenant
                                        AND tas.type_agrement_id = avi.type_agrement
                                        AND i.annee_id < avi.date_validite
                                        AND i.annee_id >= avi.annee_id

    WHERE
      ta.code = 'CONSEIL_ACADEMIQUE'
      /*@INTERVENANT_ID=i.id*/
      /*@ANNEE_ID=i.annee_id*/
  )
WHERE
  rank = 1

UNION ALL
SELECT DISTINCT "ANNEE_ID","ANNEE_AGREMENT","TYPE_AGREMENT_ID","INTERVENANT_ID","CODE_INTERVENANT","STRUCTURE_ID","OBLIGATOIRE","AGREMENT_ID","DUREE_VIE","RANK" FROM (
    SELECT
      i.annee_id                                  annee_id,
      CASE
        WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END    annee_agrement,
      tas.type_agrement_id                        type_agrement_id,
      i.id                                        intervenant_id,
      i.code                                      code_intervenant,
      a.structure_id                            structure_id,
      tas.obligatoire                             obligatoire,
      NVL(a.id, avi.agrement_id)                  agrement_id,
      tas.duree_vie                               duree_vie,
      RANK() OVER(
        PARTITION BY i.code,i.annee_id ORDER BY
        CASE
        WHEN NVL(NVL(a.id, avi.agrement_id),0) = 0
        THEN NULL
        ELSE NVL(avi.annee_id, i.annee_id) END DESC
      ) rank
    FROM
      type_agrement                   ta
      JOIN type_agrement_statut      tas ON tas.type_agrement_id = ta.id
                                        AND tas.histo_destruction IS NULL

      JOIN intervenant                 i ON i.histo_destruction IS NULL
                                        AND i.statut_id = tas.statut_intervenant_id

      JOIN                           i_s ON i_s.intervenant_id = i.id

      LEFT JOIN agrement               a ON a.type_agrement_id = ta.id
                                        AND a.intervenant_id = i.id
                                        AND a.histo_destruction IS NULL

      LEFT JOIN                      avi ON i.code = avi.code_intervenant
                                        AND tas.type_agrement_id = avi.type_agrement
                                        AND i.annee_id < avi.date_validite
                                        AND i.annee_id >= avi.annee_id


    WHERE
      ta.code = 'CONSEIL_RESTREINT'
      /*@INTERVENANT_ID=i.id*/
      /*@ANNEE_ID=i.annee_id*/
  )
WHERE
  rank = 1