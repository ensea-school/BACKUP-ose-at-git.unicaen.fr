CREATE OR REPLACE FORCE VIEW V_TBL_CONTRAT AS
SELECT
  NULL                                                                            id,
  i.annee_id                                                                      annee_id,
  i.id                                                                            intervenant_id,
  1                                                                               actif,
  CASE pce.valeur
    WHEN 'contrat_ens_composante' THEN 'er_' || i.id || '_' || str.id
    ELSE 'er_' || i.id
  END                                                                             uuid,

  str.id                                                                          structure_id,
  c.id                                                                            contrat_id,
  c.contrat_id                                                                    contrat_parent_id,
  c.type_contrat_id                                                               type_contrat_id,

  ts.id                                                                           type_service_id,
  ts.code                                                                         type_service_code,
  NULL                                                                            mission_id,
  vh.service_id                                                                   service_id,
  NULL                                                                            service_referentiel_id,

  NULL                                                                            volume_horaire_mission_id,
  vh.id                                                                           volume_horaire_id,
  NULL                                                                            volume_horaire_ref_id,

  CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END       edite,
  CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END       signe,

  c.debut_validite                                                                date_debut,
  c.fin_validite                                                                  date_fin,
  c.histo_creation                                                                date_creation,

  CASE WHEN ti.code = 'CM' THEN vh.heures ELSE 0 END                              cm,
  CASE WHEN ti.code = 'TD' THEN vh.heures ELSE 0 END                              td,
  CASE WHEN ti.code = 'TP' THEN vh.heures ELSE 0 END                              tp,
  CASE WHEN ti.code NOT IN ('CM','TD','TP') THEN vh.heures ELSE 0 END             autres,
  vh.heures                                                                       heures,
  frv.total                                                                       hetd,
  CASE WHEN ti.code NOT IN ('CM', 'TD', 'TP') THEN ti.code ELSE NULL END          autre_libelle,

  COALESCE(ep.taux_remu_id, si.taux_remu_id, CAST(ptr.valeur AS INT))             taux_remu_id,
  COALESCE(ep.taux_remu_id, si.taux_remu_id, CAST(ptr.valeur AS INT))             taux_remu_majore_id,

  0.0                                                                             taux_conges_payes
FROM
            volume_horaire          vh
       JOIN type_service            ts ON ts.code = 'ENS'
       JOIN type_volume_horaire    tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.code = 'PREVU'
       JOIN v_volume_horaire_etat vvhe ON vvhe.volume_horaire_id = vh.id
       JOIN etat_volume_horaire    evh ON evh.id = vvhe.etat_volume_horaire_id
       JOIN service                  s ON s.id = vh.service_id
       JOIN intervenant              i ON i.id = s.intervenant_id
       JOIN statut                  si ON si.id = i.statut_id
       JOIN type_intervention       ti ON ti.id = vh.type_intervention_id
       JOIN parametre              pce ON pce.nom = 'contrat_ens'
       JOIN parametre              ptr ON ptr.nom = 'taux-remu'
       JOIN element_pedagogique     ep ON ep.id = s.element_pedagogique_id
       JOIN structure              str ON ep.structure_id = str.id
       JOIN formule_resultat        fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
       JOIN formule_resultat_vh    frv ON frv.volume_horaire_id = vh.id AND frv.formule_resultat_id = fr.id
  LEFT JOIN contrat                  c ON c.id = vh.contrat_id
WHERE
  vh.histo_destruction IS NULL
  AND evh.code <> 'saisi'
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=si.id*/


UNION ALL


SELECT
  NULL                                                                            id,
  i.annee_id                                                                      annee_id,
  i.id                                                                            intervenant_id,
  1                                                                               actif,
  CASE pce.valeur
    WHEN 'contrat_ens_composante' THEN 'er_'  || i.id || '_' || str.id
    ELSE 'er_' || i.id
  END                                                                             uuid,

  str.id                                                                          structure_id,
  c.id                                                                            contrat_id,
  c.contrat_id                                                                    contrat_parent_id,
  c.type_contrat_id                                                               type_contrat_id,

  ts.id                                                                           type_service_id,
  ts.code                                                                         type_service_code,
  NULL                                                                            mission_id,
  NULL                                                                            service_id,
  vhr.service_referentiel_id                                                      service_referentiel_id,

  NULL                                                                            volume_horaire_mission_id,
  NULL                                                                            volume_horaire_id,
  vhr.id                                                                          volume_horaire_ref_id,

  CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END       edite,
  CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END       signe,

  c.debut_validite                                                                date_debut,
  c.fin_validite                                                                  date_fin,
  c.histo_creation                                                                date_creation,

  NULL                                                                            cm,
  NULL                                                                            td,
  NULL                                                                            tp,
  vhr.heures                                                                      autres,
  vhr.heures                                                                       heures,
  frvr.total                                                                      hetd,
  fon_ref.libelle_long                                                                 autre_libelle,

  COALESCE(si.taux_remu_id, CAST(ptr.valeur AS INT))                              taux_remu_id,
  COALESCE(si.taux_remu_id, CAST(ptr.valeur AS INT))                              taux_remu_majore_id,

  0.0                                                                             taux_conges_payes
FROM
            volume_horaire_ref          vhr
       JOIN type_service                ts ON ts.code = 'REF'
       JOIN type_volume_horaire        tvh ON tvh.id = vhr.type_volume_horaire_id AND tvh.code = 'PREVU'
       JOIN v_volume_horaire_ref_etat vvhe ON vvhe.volume_horaire_ref_id = vhr.id
       JOIN etat_volume_horaire        evh ON evh.id = vvhe.etat_volume_horaire_id
       JOIN service_referentiel         sr ON sr.id = vhr.service_referentiel_id
       JOIN intervenant                  i ON i.id = sr.intervenant_id
       JOIN statut                      si ON si.id = i.statut_id
       JOIN fonction_referentiel   fon_ref ON fon_ref.id = sr.fonction_id
       JOIN parametre                  pce ON pce.nom = 'contrat_ens'
       JOIN parametre                  ptr ON ptr.nom = 'taux-remu'
       JOIN structure                  str ON str.id = sr.structure_id
       JOIN formule_resultat            fr ON fr.intervenant_id = i.id AND fr.etat_volume_horaire_id = evh.id AND fr.type_volume_horaire_id = tvh.id
       JOIN formule_resultat_vh_ref   frvr ON frvr.volume_horaire_ref_id = vhr.id AND frvr.formule_resultat_id = fr.id
  LEFT JOIN contrat                      c ON c.id = vhr.contrat_id
WHERE
  vhr.histo_destruction IS NULL
  AND evh.code <> 'saisi'
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=si.id*/


UNION ALL


SELECT
  NULL                                                                            id,
  i.annee_id                                                                      annee_id,
  i.id                                                                            intervenant_id,
  1                                                                               actif,
  CASE pce.valeur
               WHEN 'contrat_mis_mission'       THEN 'm_' || i.id || '_' || m.id
               WHEN 'contrat_mis_composante'    THEN 'm_' || i.id || '_' || str.id
               WHEN 'contrat_mis_globale'       THEN 'm_' || i.id
  END                                                                             uuid,

  str.id                                                                          structure_id,
  c.id                                                                            contrat_id,
  c.contrat_id                                                                    contrat_parent_id,
  c.type_contrat_id                                                               type_contrat_id,

  ts.id                                                                           type_service_id,
  ts.code                                                                         type_service_code,
  m.id                                                                            mission_id,
  NULL                                                                            service_id,
  NULL                                                                            service_referentiel_id,

  vhm.id                                                                          volume_horaire_mission_id,
  NULL                                                                            volume_horaire_id,
  NULL                                                                            volume_horaire_ref_id,

  CASE WHEN evh.code IN ('contrat-edite','contrat-signe') THEN 1 ELSE 0 END       edite,
  CASE WHEN evh.code IN ('contrat-signe')                 THEN 1 ELSE 0 END       signe,

  c.debut_validite                                                                date_debut,
  c.fin_validite                                                                  date_fin,
  c.histo_creation                                                                date_creation,

  NULL                                                                            cm,
  NULL                                                                            td,
  NULL                                                                            tp,
  vhm.heures                                                                      autres,
  vhm.heures                                                                       heures,
  vhm.heures                                                                      hetd,
  tm.libelle                                                                      autre_libelle,

  COALESCE(si.taux_remu_id, CAST(ptr.valeur AS INT))                              taux_remu_id,
  COALESCE(si.taux_remu_id, CAST(ptr.valeur AS INT))                              taux_remu_majore_id,

  CAST(tcp.valeur AS FLOAT)                                                       taux_conges_payes
FROM
            volume_horaire_mission          vhm
       JOIN type_service                    ts ON ts.code = 'MIS'
       JOIN type_volume_horaire            tvh ON tvh.id = vhm.type_volume_horaire_id AND tvh.code = 'PREVU'
       JOIN v_volume_horaire_mission_etat vvhe ON vvhe.volume_horaire_mission_id = vhm.id
       JOIN etat_volume_horaire            evh ON evh.id = vvhe.etat_volume_horaire_id
       JOIN mission                          m ON m.id = vhm.mission_id
       JOIN intervenant                      i ON i.id = m.intervenant_id
       JOIN statut                          si ON si.id = i.statut_id
       JOIN type_mission                    tm ON tm.id = m.type_mission_id
       JOIN parametre                      pce ON pce.nom = 'contrat_mis'
       JOIN parametre                      ptr ON ptr.nom = 'taux-remu'
       JOIN parametre                      tcp ON ptr.nom = 'taux_conges_payes'
       JOIN structure                      str ON str.id = m.structure_id
  LEFT JOIN contrat                          c ON c.id = vhm.contrat_id
WHERE
  vhm.histo_destruction IS NULL
  AND evh.code <> 'saisi'
  /*@INTERVENANT_ID=i.id*/
  /*@ANNEE_ID=i.annee_id*/
  /*@STATUT_ID=si.id*/