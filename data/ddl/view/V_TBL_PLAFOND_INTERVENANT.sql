CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_INTERVENANT AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.HEURES,
  p.PLAFOND,
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)
    WHEN p.type_volume_horaire_id = 2 THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)
  END plafond_etat_id,
  COALESCE(pd.heures, 0) derogation,
  CASE WHEN p.heures > p.plafond + COALESCE(pd.heures, 0) + 0.05 THEN 1 ELSE 0 END depassement
FROM
  (
  SELECT 2 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                          annee_id,
        fr.type_volume_horaire_id           type_volume_horaire_id,
        i.id                                intervenant_id,
        fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL heures,
        si.plafond_referentiel              plafond
      FROM
        intervenant                     i
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
        JOIN statut_intervenant        si ON si.id = i.statut_id
    ) p

    UNION ALL

  SELECT 4 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                             annee_id,
        fr.type_volume_horaire_id              type_volume_horaire_id,
        i.id                                   intervenant_id,
        fr.total - fr.heures_compl_fc_majorees heures,
        si.maximum_hetd                        plafond
      FROM
        intervenant                     i
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
        JOIN statut_intervenant        si ON si.id = i.statut_id
    ) p

    UNION ALL

  SELECT 1 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                          annee_id,
        fr.type_volume_horaire_id           type_volume_horaire_id,
        i.id                                intervenant_id,
        fr.heures_compl_fc_majorees         heures,
        ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond

      FROM
             intervenant                i
        JOIN annee                      a ON a.id = i.annee_id
        JOIN statut_intervenant        si ON si.id = i.statut_id
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    ) p

    UNION ALL

  SELECT 5 PLAFOND_ID, p.* FROM (
    SELECT
        i.annee_id                          annee_id,
        fr.type_volume_horaire_id           type_volume_horaire_id,
        fr.intervenant_id                   intervenant_id,
        fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures,
        si.plafond_hc_hors_remu_fc          plafond
      FROM
             intervenant                i
        JOIN statut_intervenant        si ON si.id = i.statut_id
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    ) p

    UNION ALL

  SELECT 8 PLAFOND_ID, p.* FROM (
    SELECT
        t.annee_id,
        t.type_volume_horaire_id,
        t.intervenant_id,
        t.heures,
        si.plafond_hc_fi_hors_ead           plafond
      FROM
        (
          SELECT
            fr.type_volume_horaire_id           type_volume_horaire_id,
            i.annee_id                          annee_id,
            i.id                                intervenant_id,
            i.statut_id                         statut_intervenant_id,
            si.plafond_hc_fi_hors_ead           plafond,
            SUM(frvh.heures_compl_fi)           heures
          FROM
            intervenant                     i
            JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
            JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
            JOIN formule_resultat_vh     frvh ON frvh.formule_resultat_id = fr.id
            JOIN volume_horaire            vh ON vh.id = frvh.volume_horaire_id
            JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
            JOIN statut_intervenant        si ON si.id = i.statut_id
          WHERE
            ti.regle_foad = 0
          GROUP BY
            fr.type_volume_horaire_id,
            i.annee_id,
            i.id,
            i.statut_id,
            si.plafond_hc_fi_hors_ead
        ) t
          JOIN statut_intervenant si ON si.id = t.statut_intervenant_id
    ) p
  ) p
  JOIN intervenant i ON i.id = p.intervenant_id
  LEFT JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND p.annee_id = pa.annee_id
  LEFT JOIN plafond_statut ps ON ps.plafond_id = p.plafond_id AND ps.statut_intervenant_id = i.statut_id AND ps.annee_id = i.annee_id AND ps.histo_destruction IS NULL
  LEFT JOIN plafond_derogation pd ON pd.plafond_id = p.plafond_id AND pd.intervenant_id = p.intervenant_id AND pd.histo_destruction IS NULL
WHERE
  CASE
    WHEN p.type_volume_horaire_id = 1 THEN COALESCE(ps.plafond_etat_prevu_id,pa.plafond_etat_prevu_id)
    WHEN p.type_volume_horaire_id = 2 THEN COALESCE(ps.plafond_etat_realise_id, pa.plafond_etat_realise_id)
  END IS NOT NULL
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/
  /*@PLAFOND_ETAT_ID=p.PLAFOND_ETAT_ID*/