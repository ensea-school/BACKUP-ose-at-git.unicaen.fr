CREATE OR REPLACE FORCE VIEW V_EXP_HETD_CENTRE_COUT AS
WITH t AS (
  SELECT
    tvh.id                              type_volume_horaire_id,
    evh.id                              etat_volume_horaire_id,
    a.id                                annee_id,
    i.id                                intervenant_id,
    si.id                               statut_id,
    ti.id                               type_intervenant_id,
    g.id                                grade_id,
    str.id                              structure_id,
    gtf.id                              groupe_type_formation_id,
    tf.id                               type_formation_id,
    e.id                                etape_id,

    tvh.code                            type_volume_horaire,
    evh.code                            etat_volume_horaire,
    a.libelle                           annee,
    i.code                              code_intervenant,
    i.nom_usuel || ' ' || i.prenom      intervenant,
    si.libelle                          statut_intervenant,
    ti.libelle                          type_intervenant,
    g.libelle_long                      grade,
    str.libelle_court                   structure_enseignement,
    gtf.libelle_long                    groupe_type_formation,
    tf.libelle_long                     type_formation,
    e.source_code                       code_formation,

    CASE WHEN mep.id IS NULL THEN frs.total ELSE mep.heures END
                                        total_hetd,

    cc.source_code || ' ' || cc.libelle centre_couts,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(mep.date_mise_en_paiement,SYSDATE) ) * CASE WHEN mep.id IS NULL THEN frs.total ELSE mep.heures END
                                        total_euros
  FROM
              formule_resultat_service  frs
         JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
         JOIN etat_volume_horaire       evh ON evh.id = fr.etat_volume_horaire_id
         JOIN type_volume_horaire       tvh ON tvh.id = fr.type_volume_horaire_id
         JOIN intervenant                 i ON i.id = fr.intervenant_id
         JOIN annee                       a ON a.id = i.annee_id
         JOIN statut                     si ON si.id = i.statut_id
         JOIN type_intervenant           ti ON ti.id = si.type_intervenant_id
         JOIN service                     s ON s.id = frs.service_id
    LEFT JOIN grade                       g ON g.id = i.grade_id
    LEFT JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id
         JOIN structure                 str ON str.id = NVL(ep.structure_id, i.structure_id)
    LEFT JOIN etape                       e ON e.id = ep.etape_id
    LEFT JOIN type_formation             tf ON tf.id = e.type_formation_id
    LEFT JOIN groupe_type_formation     gtf ON gtf.id = tf.groupe_id
    LEFT JOIN mise_en_paiement          mep ON mep.formule_res_service_id = frs.id
                                           AND mep.histo_destruction IS NULL

    LEFT JOIN centre_cout                cc ON cc.id = MEP.CENTRE_COUT_ID

  UNION ALL

  SELECT
    type_volume_horaire_id, etat_volume_horaire_id, annee_id, intervenant_id, statut_id, type_intervenant_id,
    grade_id, structure_id, groupe_type_formation_id, type_formation_id, etape_id,

    type_volume_horaire, etat_volume_horaire, annee, code_intervenant, intervenant, statut_intervenant,
    type_intervenant, grade, structure_enseignement, groupe_type_formation, type_formation,
    code_formation, total_hetd, centre_couts,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( SYSDATE ) * total_hetd total_euros
  FROM (
  SELECT
    tvh.id                              type_volume_horaire_id,
    evh.id                              etat_volume_horaire_id,
    a.id                                annee_id,
    i.id                                intervenant_id,
    si.id                               statut_id,
    ti.id                               type_intervenant_id,
    g.id                                grade_id,
    str.id                              structure_id,
    gtf.id                              groupe_type_formation_id,
    tf.id                               type_formation_id,
    e.id                                etape_id,

    tvh.code                            type_volume_horaire,
    evh.code                            etat_volume_horaire,
    a.libelle                           annee,
    i.code                              code_intervenant,
    i.nom_usuel || ' ' || i.prenom      intervenant,
    si.libelle                          statut_intervenant,
    ti.libelle                          type_intervenant,
    g.libelle_long                      grade,
    str.libelle_court                   structure_enseignement,
    gtf.libelle_long                    groupe_type_formation,
    tf.libelle_long                     type_formation,
    e.source_code                       code_formation,
    frs.total - SUM(mep.heures) OVER (PARTITION BY frs.id) total_hetd,
    RANK() OVER ( PARTITION BY frs.id ORDER BY mep.id) ordre,
    null centre_couts
  FROM
              formule_resultat_service  frs
         JOIN formule_resultat           fr ON fr.id = frs.formule_resultat_id
         JOIN etat_volume_horaire       evh ON evh.id = fr.etat_volume_horaire_id
         JOIN type_volume_horaire       tvh ON tvh.id = fr.type_volume_horaire_id
         JOIN intervenant                 i ON i.id = fr.intervenant_id
         JOIN annee                       a ON a.id = i.annee_id
         JOIN statut                     si ON si.id = i.statut_id
         JOIN type_intervenant           ti ON ti.id = si.type_intervenant_id
         JOIN service                     s ON s.id = frs.service_id
    LEFT JOIN grade                       g ON g.id = i.grade_id
    LEFT JOIN element_pedagogique        ep ON ep.id = s.element_pedagogique_id
         JOIN structure                 str ON str.id = NVL(ep.structure_id, i.structure_id)
    LEFT JOIN etape                       e ON e.id = ep.etape_id
    LEFT JOIN type_formation             tf ON tf.id = e.type_formation_id
    LEFT JOIN groupe_type_formation     gtf ON gtf.id = tf.groupe_id
    LEFT JOIN mise_en_paiement          mep ON mep.formule_res_service_id = frs.id
                                           AND mep.histo_destruction IS NULL
  ) t WHERE ordre = 1 AND total_hetd > 0
)
SELECT
  type_volume_horaire_id,
  etat_volume_horaire_id,
  annee_id,
  intervenant_id,
  statut_id,
  type_intervenant_id,
  grade_id,
  structure_id,
  groupe_type_formation_id,
  type_formation_id,
  etape_id,

  type_volume_horaire,
  etat_volume_horaire,
  annee,
  code_intervenant,
  intervenant,
  statut_intervenant,
  type_intervenant,
  grade,
  structure_enseignement,
  groupe_type_formation,
  type_formation,
  code_formation,
  SUM(total_hetd) total_hetd,
  centre_couts,
  SUM(total_euros) total_euros
FROM
  t
GROUP BY
  type_volume_horaire_id,
  etat_volume_horaire_id,
  annee_id,
  intervenant_id,
  statut_id,
  type_intervenant_id,
  grade_id,
  structure_id,
  groupe_type_formation_id,
  type_formation_id,
  etape_id,

  type_volume_horaire,
  etat_volume_horaire,
  annee,
  code_intervenant,
  intervenant,
  statut_intervenant,
  type_intervenant,
  grade,
  structure_enseignement,
  groupe_type_formation,
  type_formation,
  code_formation,
  centre_couts