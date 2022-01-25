CREATE OR REPLACE FORCE VIEW V_EXPORT_PILOTAGE_ECARTS_ETATS AS
SELECT
  t3.annee_id annee_id,
  t3.annee_id || '-' || (t3.annee_id+1) annee,
  t3.etat,
  t3.type_heures_id,
  t3.type_heures,
  s.id structure_id,
  s.libelle_court structure,
  i.id intervenant_id,
  ti.libelle intervenant_type,
  i.source_code intervenant_code,
  i.prenom || ' ' || i.nom_usuel intervenant,
  t3.hetd_payables
FROM

(
SELECT
  annee_id,
  etat,
  type_heures_id,
  type_heures,
  structure_id,
  intervenant_id,
  sum(hetd) hetd_payables
FROM (
  SELECT
    annee_id,
    LOWER(tvh.code) || '-' || evh.code etat,
    10*tvh.ordre + evh.ordre ordre,
    type_heures_id,
    type_heures,
    structure_id,
    intervenant_id,
    SUM(hetd) hetd
  FROM (
    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fi) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fi'
      LEFT JOIN element_pedagogique   ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id

    UNION ALL

    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fa) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fa'
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id

    UNION ALL

    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE(ep.structure_id,i.structure_id) structure_id,
      fr.intervenant_id,
      SUM(frs.heures_compl_fc) hetd
    FROM
           formule_resultat_service  frs
      JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
      JOIN service                     s ON s.id = frs.service_id
      JOIN intervenant                 i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'fc'
      LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      ep.structure_id,
      i.structure_id

    UNION ALL

    SELECT
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id type_heures_id,
      th.code type_heures,
      sr.structure_id,
      fr.intervenant_id,
      sum( frsr.heures_compl_referentiel ) hetd
    FROM
           formule_resultat_service_ref  frsr
      JOIN formule_resultat                fr ON fr.id = frsr.formule_resultat_id
      JOIN service_referentiel             sr ON sr.id = frsr.service_referentiel_id
      JOIN intervenant                      i ON i.id = fr.intervenant_id
      JOIN type_heures                th ON th.code = 'referentiel'
    GROUP BY
      i.annee_id,
      fr.type_volume_horaire_id,
      fr.etat_volume_horaire_id,
      th.id, th.code,
      fr.intervenant_id,
      sr.structure_id
  ) t1
    JOIN type_volume_horaire            tvh ON tvh.id = t1.type_volume_horaire_id
    JOIN etat_volume_horaire            evh ON evh.id = t1.etat_volume_horaire_id
  GROUP BY
    annee_id, tvh.code, evh.code, tvh.ordre, evh.ordre, type_heures_id, type_heures, structure_id, intervenant_id

  UNION ALL

  SELECT
    annee_id,
    etat,
    ordre,
    type_heures_id,
    type_heures,
    structure_id,
    intervenant_id,
    SUM(hetd) hetd
  FROM (
    SELECT
      i.annee_id,
      'demande-mise-en-paiement' etat,
      90 ordre,
      th.id   type_heures_id,
      th.code type_heures,
      COALESCE( sr.structure_id, ep.structure_id, i.structure_id ) structure_id,
      i.id intervenant_id,
      mep.heures hetd
    FROM
                mise_en_paiement              mep
           JOIN type_heures                    th ON th.id = mep.type_heures_id
           JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
      LEFT JOIN formule_resultat_service      frs ON frs.id = mep.formule_res_service_id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
      LEFT JOIN formule_resultat               fr ON fr.id = COALESCE(frs.formule_resultat_id, frsr.formule_resultat_id)
      LEFT JOIN service                         s ON s.id = frs.service_id
      LEFT JOIN element_pedagogique            ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
      LEFT JOIN intervenant                     i ON i.id = fr.intervenant_id
    WHERE
      mep.histo_destruction IS NULL
      AND th.eligible_extraction_paie = 1

    UNION ALL

    SELECT
      i.annee_id,
      'mise-en-paiement' etat,
      91 ordre,
      th.id type_heures_id,
      th.code type_heures,
      COALESCE( sr.structure_id, ep.structure_id, i.structure_id ) structure_id,
      i.id intervenant_id,
      mep.heures hetd
    FROM
                mise_en_paiement              mep
           JOIN type_heures                    th ON th.id = mep.type_heures_id
           JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
      LEFT JOIN formule_resultat_service      frs ON frs.id = mep.formule_res_service_id
      LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
      LEFT JOIN formule_resultat               fr ON fr.id = COALESCE(frs.formule_resultat_id, frsr.formule_resultat_id)
      LEFT JOIN service                         s ON s.id = frs.service_id
      LEFT JOIN element_pedagogique            ep ON ep.id = s.element_pedagogique_id
      LEFT JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
      LEFT JOIN intervenant                     i ON i.id = fr.intervenant_id
    WHERE
      mep.histo_destruction IS NULL
      AND th.eligible_extraction_paie = 1
      AND mep.PERIODE_PAIEMENT_ID IS NOT NULL
  ) t1
  GROUP BY
    annee_id, etat, ordre, type_heures_id, type_heures, structure_id, intervenant_id
) t2
GROUP BY
  annee_id,
  etat, ordre
  ,type_heures_id, type_heures
  ,structure_id
  ,intervenant_id
ORDER BY
  annee_id, ordre

) t3
  JOIN intervenant       i ON i.id = t3.intervenant_id
  JOIN statut           si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN structure         s ON s.id = t3.structure_id