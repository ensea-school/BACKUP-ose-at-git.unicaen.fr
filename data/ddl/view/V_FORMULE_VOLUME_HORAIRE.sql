CREATE OR REPLACE FORCE VIEW V_FORMULE_VOLUME_HORAIRE AS
SELECT
  rownum ordre,
  t.ID,
  t.VOLUME_HORAIRE_ID,
  t.VOLUME_HORAIRE_REF_ID,
  t.SERVICE_ID,
  t.SERVICE_REFERENTIEL_ID,
  t.INTERVENANT_ID,
  t.TYPE_INTERVENTION_ID,
  t.TYPE_VOLUME_HORAIRE_ID,
  t.ETAT_VOLUME_HORAIRE_ID,
  t.type_volume_horaire_code,
  t.TAUX_FI,
  t.TAUX_FA,
  t.TAUX_FC,
  t.structure_id,
  t.structure_code,
  t.structure_is_affectation,
  t.structure_is_univ,
  t.PONDERATION_SERVICE_DU,
  t.PONDERATION_SERVICE_COMPL,
  t.SERVICE_STATUTAIRE,
  t.HEURES,
  t.HORAIRE_DEBUT,
  t.HORAIRE_FIN,
  t.TYPE_INTERVENTION_CODE,
  t.TAUX_SERVICE_DU,
  t.TAUX_SERVICE_COMPL
FROM (
SELECT
  to_number( 1 || vh.id )                                              id,
  vh.id                                                                volume_horaire_id,
  null                                                                 volume_horaire_ref_id,
  s.id                                                                 service_id,
  null                                                                 service_referentiel_id,
  s.intervenant_id                                                     intervenant_id,
  ti.id                                                                type_intervention_id,
  vh.type_volume_horaire_id                                            type_volume_horaire_id,
  vhe.etat_volume_horaire_id                                           etat_volume_horaire_id,

  tvh.code                                                             type_volume_horaire_code,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fi ELSE 1 END               taux_fi,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fa ELSE 0 END               taux_fa,
  CASE WHEN ep.id IS NOT NULL THEN ep.taux_fc ELSE 0 END               taux_fc,
  str.id                                                               structure_id,
  str.code                                                             structure_code,
  CASE WHEN COALESCE(str.id,0) = COALESCE(i.structure_id,0)      THEN 1 ELSE 0 END structure_is_affectation,
  CASE WHEN COALESCE(str.id,0) = COALESCE(to_number(p.valeur),0) THEN 1 ELSE 0 END structure_is_univ,
  ep.ponderation_service_du                                            ponderation_service_du,
  ep.ponderation_service_compl                                         ponderation_service_compl,
  COALESCE(tf.service_statutaire,1)                                    service_statutaire,

  vh.heures                                                            heures,
  vh.horaire_debut                                                     horaire_debut,
  vh.horaire_fin                                                       horaire_fin,
  ti.code                                                              type_intervention_code,
  COALESCE(tis.taux_hetd_service,ti.taux_hetd_service,1)               taux_service_du,
  COALESCE(tis.taux_hetd_complementaire,ti.taux_hetd_complementaire,1) taux_service_compl
FROM
            volume_horaire            vh
       JOIN parametre                  p ON p.nom = 'structure_univ'
       JOIN service                    s ON s.id = vh.service_id
       JOIN intervenant                i ON i.id = s.intervenant_id AND i.histo_destruction IS NULL
       JOIN type_intervention         ti ON ti.id = vh.type_intervention_id
       JOIN v_volume_horaire_etat    vhe ON vhe.volume_horaire_id = vh.id
       JOIN type_volume_horaire      tvh ON tvh.id = vh.type_volume_horaire_id

  LEFT JOIN (
    SELECT
      ep.id,
      ep.structure_id,
      ep.etape_id,
      ep.taux_fi,
      ep.taux_fa,
      ep.taux_fc,
      MAX(COALESCE( m.ponderation_service_du, 1))                          ponderation_service_du,
      MAX(COALESCE( m.ponderation_service_compl, 1))                       ponderation_service_compl
    FROM
      element_pedagogique ep
      LEFT JOIN element_modulateur        em ON em.element_id = ep.id
                                            AND em.histo_destruction IS NULL
      LEFT JOIN modulateur                 m ON m.id = em.modulateur_id
    GROUP BY
      ep.id,
      ep.structure_id,
      ep.etape_id,
      ep.taux_fi,
      ep.taux_fa,
      ep.taux_fc
  )                                   ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN structure                str ON str.id = ep.structure_id
  LEFT JOIN etape                      e ON e.id = ep.etape_id
  LEFT JOIN type_formation            tf ON tf.id = e.type_formation_id
  LEFT JOIN type_intervention_statut tis ON tis.type_intervention_id = ti.id AND tis.statut_id = i.statut_id
WHERE
  vh.histo_destruction IS NULL
  AND s.histo_destruction IS NULL
  AND vh.heures <> 0
  AND vh.motif_non_paiement_id IS NULL
  AND s.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, s.intervenant_id )

UNION ALL

SELECT
  to_number( 2 || vhr.id )          id,
  null                              volume_horaire_id,
  vhr.id                            volume_horaire_ref_id,
  null                              service_id,
  sr.id                             service_referentiel_id,
  sr.intervenant_id                 intervenant_id,
  null                              type_intervention_id,
  vhr.type_volume_horaire_id        type_volume_horaire_id,
  evh.id                            etat_volume_horaire_id,

  tvh.code                          type_volume_horaire_code,
  0                                 taux_fi,
  0                                 taux_fa,
  0                                 taux_fc,
  s.id                              structure_id,
  s.code                            structure_code,
  CASE WHEN COALESCE(sr.structure_id,0) = COALESCE(i.structure_id,0)      THEN 1 ELSE 0 END structure_is_affectation,
  CASE WHEN COALESCE(sr.structure_id,0) = COALESCE(to_number(p.valeur),0) THEN 1 ELSE 0 END structure_is_univ,
  1                                 ponderation_service_du,
  1                                 ponderation_service_compl,
  COALESCE(fr.service_statutaire,1) service_statutaire,

  vhr.heures                        heures,
  vhr.horaire_debut                 horaire_debut,
  vhr.horaire_fin                   horaire_fin,
  null                              type_intervention_code,
  1                                 taux_service_du,
  1                                 taux_service_compl
FROM
            volume_horaire_ref          vhr
       JOIN parametre                     p ON p.nom = 'structure_univ'
       JOIN service_referentiel          sr ON sr.id = vhr.service_referentiel_id
       JOIN intervenant                   i ON i.id = sr.intervenant_id AND i.histo_destruction IS NULL
       JOIN v_volume_horaire_ref_etat  vher ON vher.volume_horaire_ref_id = vhr.id
       JOIN etat_volume_horaire         evh ON evh.id = vher.etat_volume_horaire_id
       JOIN fonction_referentiel         fr ON fr.id = sr.fonction_id
       JOIN type_volume_horaire         tvh ON tvh.id = vhr.type_volume_horaire_id
  LEFT JOIN structure                     s ON s.id = sr.structure_id
WHERE
  vhr.histo_destruction IS NULL
  AND sr.histo_destruction IS NULL
  AND vhr.heures <> 0
  AND sr.intervenant_id = COALESCE( OSE_FORMULE.GET_INTERVENANT_ID, sr.intervenant_id )

ORDER BY
  horaire_fin, horaire_debut, volume_horaire_id, volume_horaire_ref_id
) t