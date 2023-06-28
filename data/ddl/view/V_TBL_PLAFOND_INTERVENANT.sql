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
  SELECT 1 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                          annee_id,
        fr.type_volume_horaire_id           type_volume_horaire_id,
        fr.intervenant_id                   intervenant_id,
        fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures
      FROM
             intervenant                i
        JOIN statut                    si ON si.id = i.statut_id
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    ) p

    UNION ALL

  SELECT 2 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                             annee_id,
        fr.type_volume_horaire_id              type_volume_horaire_id,
        i.id                                   intervenant_id,
        fr.total - fr.heures_compl_fc_majorees heures
      FROM
        intervenant                     i
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
        JOIN statut                    si ON si.id = i.statut_id
    ) p

    UNION ALL

  SELECT 4 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                annee_id,
        fr.type_volume_horaire_id type_volume_horaire_id,
        i.id                      intervenant_id,
        SUM(frvh.heures_compl_fi) heures
      FROM
        intervenant                     i
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
        JOIN formule_resultat_vh     frvh ON frvh.formule_resultat_id = fr.id
        JOIN volume_horaire            vh ON vh.id = frvh.volume_horaire_id
        JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
        JOIN statut                    si ON si.id = i.statut_id
      WHERE
        ti.regle_foad = 0
      GROUP BY
        fr.type_volume_horaire_id,
        i.annee_id,
        i.id,
        i.statut_id
    ) p

    UNION ALL

  SELECT 8 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                             annee_id,
        fr.type_volume_horaire_id              type_volume_horaire_id,
        i.id                                   intervenant_id,
        fr.service_referentiel + fr.heures_compl_referentiel heures
      FROM
        intervenant                     i
        JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
        JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
        JOIN statut                    si ON si.id = i.statut_id
    ) p

    UNION ALL

  SELECT 9 PLAFOND_ID, NULL PLAFOND, NULL PLAFOND_ETAT_ID, p.* FROM (
    SELECT
        i.annee_id                annee_id,
        vh.type_volume_horaire_id type_volume_horaire_id,
        i.id                      intervenant_id,
        SUM(vh.heures)            heures
      FROM
        volume_horaire vh
        JOIN service s ON s.id = vh.service_id
        JOIN intervenant i ON i.id = s.intervenant_id
        JOIN statut si ON si.id = i.statut_id
      WHERE
        vh.histo_destruction IS NULL
        AND i.histo_destruction IS NULL
        AND vh.motif_non_paiement_id IS NULL
        AND si.code IN ('IMP')
      GROUP BY
        i.annee_id,
        vh.type_volume_horaire_id,
        i.id,
        i.statut_id
      HAVING
        SUM(vh.heures) >= 0
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