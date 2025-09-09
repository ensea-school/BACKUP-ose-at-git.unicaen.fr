CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_INTERVENANT AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.HEURES,
  COALESCE(p.PLAFOND,ps.heures,0) PLAFOND,
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN ps.plafond_etat_prevu_id
    WHEN p.type_volume_horaire_id = 2 THEN ps.plafond_etat_realise_id
    ELSE COALESCE(p.plafond_etat_id,1)
  END plafond_etat_id,
  COALESCE(pd.heures, 0) derogation,
  CASE WHEN p.heures > COALESCE(p.PLAFOND,ps.heures,0) + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement
FROM
  (
  SELECT 11 PLAFOND_ID, p.ANNEE_ID, p.TYPE_VOLUME_HORAIRE_ID, p.INTERVENANT_ID, p.HEURES, p.PLAFOND, NULL PLAFOND_ETAT_ID FROM (
    SELECT
        annee_id, intervenant_id, type_volume_horaire_id, plafond, heures
      FROM
        (
        SELECT
          annee_id, intervenant_id, type_volume_horaire_id, plafond, heures, DIFF,
          max(DIFF) OVER (PARTITION BY  annee_id, intervenant_id, type_volume_horaire_id) MDIFF
        FROM
          (
          SELECT
            tm.annee_id,
            m.intervenant_id,
            tvh.id type_volume_horaire_id,
            heures_prevues_validees plafond,
            heures_realisees_saisies heures,
            heures_realisees_saisies - heures_prevues_validees diff
          FROM
            tbl_mission tm
            JOIN mission m ON m.id = tm.mission_id
            JOIN type_volume_horaire tvh ON tvh.code = 'REALISE'
          WHERE
            tm.heures_realisees_saisies > tm.heures_prevues_validees
          ) t
        ) t
      WHERE
        diff = mdiff
    ) p

    UNION ALL

  SELECT 10 PLAFOND_ID, p.ANNEE_ID, p.TYPE_VOLUME_HORAIRE_ID, p.INTERVENANT_ID, p.HEURES, p.PLAFOND, NULL PLAFOND_ETAT_ID FROM (
    SELECT
        i.annee_id annee_id,
        type_volume_horaire_id,
        intervenant_id,
        heures,
        plafond
      FROM
        (
        SELECT
          intervenant_id,
          type_volume_horaire_id,
          tranche,
          sum(heures) heures,
          least(min(plafond_tranche_mission), min(plafond_tranche)) plafond
        FROM
          (
          SELECT
            m.intervenant_id                                         intervenant_id,
            vhm.type_volume_horaire_id                               type_volume_horaire_id,
            to_char( vhm.horaire_debut, 'YYYY-mm' )                  tranche,
            vhm.heures                                               heures,
            ROUND(CASE to_char( vhm.horaire_debut, 'mm' ) WHEN '07' THEN 150 WHEN '08' THEN 150 ELSE 67 END / 30 * (m.date_fin - m.date_debut),2) plafond_tranche_mission,
            CASE to_char( vhm.horaire_debut, 'mm' ) WHEN '07' THEN 150 WHEN '08' THEN 150 ELSE 67 END plafond_tranche
          FROM
            volume_horaire_mission vhm
            JOIN type_volume_horaire tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code = 'REALISE'
            JOIN mission m ON m.id = vhm.mission_id AND m.histo_destruction IS NULL
          WHERE
            vhm.histo_destruction IS NULL
          ) t
        GROUP BY
          intervenant_id,
          type_volume_horaire_id,
          tranche
      ) t
      JOIN intervenant i ON i.id = t.intervenant_id
      WHERE
        heures > plafond
        AND rownum = 1
    ) p
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_statut ps ON ps.plafond_id = p.plafond_id AND ps.statut_id = i.statut_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL
WHERE
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN ps.plafond_etat_prevu_id
    WHEN p.type_volume_horaire_id = 2 THEN ps.plafond_etat_realise_id
  END IS NOT NULL
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/