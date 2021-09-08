CREATE OR REPLACE FORCE VIEW V_TBL_PLAFOND_INTERVENANT AS
SELECT
  p.PLAFOND_ID,
  p.ANNEE_ID,
  p.TYPE_VOLUME_HORAIRE_ID,
  p.INTERVENANT_ID,
  p.HEURES,
  p.PLAFOND,
  p.DEROGATION
FROM
(
  SELECT 6 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id,
      t.intervenant_id,
      t.type_volume_horaire_id,
      AVG(t.plafond)                      plafond,
      AVG(t.heures)                       heures
    FROM
      (
        SELECT
          vhr.type_volume_horaire_id        type_volume_horaire_id,
          sr.intervenant_id                 intervenant_id,
          fr.plafond                        plafond,
          fr.id                             fr_id,
          SUM(vhr.heures)                   heures
        FROM
          service_referentiel       sr
            JOIN fonction_referentiel      frf ON frf.id = sr.fonction_id
            JOIN fonction_referentiel      fr ON fr.id = frf.parent_id
            JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        WHERE
            sr.histo_destruction IS NULL
        GROUP BY
          vhr.type_volume_horaire_id,
          sr.intervenant_id,
          fr.plafond,
          fr.id
      ) t
        JOIN intervenant i ON i.id = t.intervenant_id
    WHERE
        t.heures > t.plafond + 0.05
      /*@INTERVENANT_ID=i.id*/
    GROUP BY
      t.type_volume_horaire_id,
      i.annee_id,
      t.intervenant_id
  ) p

  UNION ALL

  SELECT 3 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id,
      t.intervenant_id,
      t.type_volume_horaire_id,
      AVG(t.plafond)                      plafond,
      AVG(t.heures)                       heures
    FROM
      (
      SELECT
        vhr.type_volume_horaire_id        type_volume_horaire_id,
        sr.intervenant_id                 intervenant_id,
        fr.plafond                        plafond,
        fr.id                             fr_id,
        SUM(vhr.heures)                   heures
      FROM
             service_referentiel       sr
        JOIN fonction_referentiel      fr ON fr.id = sr.fonction_id
        JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
      WHERE
        sr.histo_destruction IS NULL
      GROUP BY
        vhr.type_volume_horaire_id,
        sr.intervenant_id,
        fr.plafond,
        fr.id
      ) t
      JOIN intervenant i ON i.id = t.intervenant_id
    WHERE
      t.heures > t.plafond + 0.05
      /*@INTERVENANT_ID=i.id*/
    GROUP BY
      t.type_volume_horaire_id,
      i.annee_id,
      t.intervenant_id
  ) p

  UNION ALL

  SELECT 2 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id                          annee_id,
      i.id                                intervenant_id,
      fr.type_volume_horaire_id           type_volume_horaire_id,
      si.plafond_referentiel              plafond,
      fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL heures
    FROM
      intervenant                     i
      JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
      JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
      JOIN statut_intervenant        si ON si.id = i.statut_id
    WHERE
      fr.SERVICE_REFERENTIEL + fr.HEURES_COMPL_REFERENTIEL > si.plafond_referentiel + 0.05
      /*@INTERVENANT_ID=i.id*/
  ) p

  UNION ALL

  SELECT 7 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      t.type_volume_horaire_id,
      t.annee_id,
      t.intervenant_id,
      t.plafond           plafond,
      t.heures            heures
    FROM
      (
        SELECT DISTINCT
          vhr.type_volume_horaire_id        type_volume_horaire_id,
          i.annee_id                        annee_id,
          i.id                              intervenant_id,
          s.plafond_referentiel             plafond,
          s.id                              structure_id,
          s.libelle_court                   structure_libelle,
          SUM(vhr.heures) OVER (PARTITION BY s.id,vhr.type_volume_horaire_id,i.annee_id) heures
        FROM
                 service_referentiel       sr
            JOIN intervenant                i ON i.id = sr.intervenant_id
            JOIN structure                  s ON s.id = sr.structure_id AND s.plafond_referentiel IS NOT NULL
            JOIN volume_horaire_ref       vhr ON vhr.service_referentiel_id = sr.id AND vhr.histo_destruction IS NULL
        WHERE
            sr.histo_destruction IS NULL
      ) t
    WHERE
        t.heures > t.plafond + 0.05
      /*@INTERVENANT_ID=i.id*/
  ) p

  UNION ALL

  SELECT 8 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      t.type_volume_horaire_id,
      t.annee_id,
      t.intervenant_id,
      si.plafond_hc_fi_hors_ead           plafond,
      t.heures
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
          /*@INTERVENANT_ID=i.id*/
        GROUP BY
          fr.type_volume_horaire_id,
          i.annee_id,
          i.id,
          i.statut_id,
          si.plafond_hc_fi_hors_ead
      ) t
        JOIN statut_intervenant si ON si.id = t.statut_intervenant_id
    WHERE
      t.heures > si.plafond_hc_fi_hors_ead + 0.05
  ) p

  UNION ALL

  SELECT 4 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      fr.type_volume_horaire_id           type_volume_horaire_id,
      i.annee_id                          annee_id,
      i.id                                intervenant_id,
      si.maximum_hetd                     plafond,
      fr.total                            heures
    FROM
      intervenant                     i
      JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
      JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
      JOIN statut_intervenant        si ON si.id = i.statut_id
    WHERE
      fr.total - fr.heures_compl_fc_majorees > si.maximum_hetd + 0.05
      /*@INTERVENANT_ID=i.id*/
  ) p

  UNION ALL

  SELECT 1 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      i.annee_id                          annee_id,
      i.id                                intervenant_id,
      fr.type_volume_horaire_id           type_volume_horaire_id,
      ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) plafond,
      fr.heures_compl_fc_majorees         heures
    FROM
           intervenant                i
      JOIN annee                      a ON a.id = i.annee_id
      JOIN statut_intervenant        si ON si.id = i.statut_id
      JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
      JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    WHERE
      fr.heures_compl_fc_majorees > ROUND( (COALESCE(si.plafond_hc_remu_fc,0) - COALESCE(i.montant_indemnite_fc,0)) / a.taux_hetd, 2 ) + 0.05
      /*@INTERVENANT_ID=i.id*/
  ) p

  UNION ALL

  SELECT 5 PLAFOND_ID, 0 DEROGATION, p.* FROM (
    SELECT
      fr.type_volume_horaire_id           type_volume_horaire_id,
      i.annee_id                          annee_id,
      fr.intervenant_id                   intervenant_id,
      si.plafond_hc_hors_remu_fc          plafond,
      fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel heures
    FROM
           intervenant                i
      JOIN statut_intervenant        si ON si.id = i.statut_id
      JOIN etat_volume_horaire      evh ON evh.code = 'saisi'
      JOIN formule_resultat          fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id
    WHERE
      (fr.heures_compl_fi + fr.heures_compl_fc + fr.heures_compl_fa + fr.heures_compl_referentiel) > si.plafond_hc_hors_remu_fc + 0.05
      /*@INTERVENANT_ID=i.id*/
  ) p
) p
JOIN plafond_application pa ON pa.plafond_id = p.plafond_id AND pa.type_volume_horaire_id = p.type_volume_horaire_id AND p.annee_id BETWEEN COALESCE(pa.annee_debut_id,p.annee_id) AND COALESCE(pa.annee_fin_id,p.annee_id)
WHERE
  1=1
  /*@PLAFOND_ID=p.PLAFOND_ID*/
  /*@ANNEE_ID=p.ANNEE_ID*/
  /*@TYPE_VOLUME_HORAIRE_ID=p.TYPE_VOLUME_HORAIRE_ID*/
  /*@INTERVENANT_ID=p.INTERVENANT_ID*/