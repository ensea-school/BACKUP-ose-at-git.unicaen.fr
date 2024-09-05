CREATE OR REPLACE FORCE VIEW V_EXPORT_MISSION AS
SELECT
  NULL                              id,
  annee_id,
  mission_id,
  NULL                              service_id,
  NULL                              service_referentiel_id,
  intervenant_id,
  type_volume_horaire_id,
  etat_volume_horaire_id,
  NULL                              element_pedagogique_id,
  NULL                              etablissement_id,
  structure_aff_id,
  structure_aff_ids,
  structure_ens_id,
  structure_ens_ids,
  NULL                              periode_id,
  NULL                              type_intervention_id,
  NULL                              fonction_referentiel_id,
  NULL                              motif_non_paiement_id,
  NULL                              tag_id,

  type_etat,
  date_extraction,
  annee,
  code_intervenant,
  code_rh,
  intervenant_nom_prenom,
  date_naissance,
  email_perso,
  composante_service,
  type_mission,
  libelle,
  date_debut,
  date_fin,
  heures,
  taux_remu,
  taux_remu_majore,
  heures_formation,
  mois_realisation,
  SUM(
      CASE etat_volume_horaire_code
        WHEN 'saisi' THEN heures_realisees
        ELSE CASE WHEN valide = 1 THEN heures_realisees ELSE 0 END
        END
  ) heures_realisees
FROM (
       SELECT
         a.id                                      annee_id,
         i.id                                      intervenant_id,
         m.id                                      mission_id,
         tvh.id                                    type_volume_horaire_id,
         evh.id                                    etat_volume_horaire_id,
         istr.id                                   structure_aff_id,
         istr.ids                                  structure_aff_ids,
         str.id                                    structure_ens_id,
         str.ids                                   structure_ens_ids,

         evh.code                                  etat_volume_horaire_code,

         tvh.libelle || ' ' || lower(evh.libelle)  type_etat,
         sysdate                                   date_extraction,
         a.libelle                                 annee,
         i.code                                    code_intervenant,
         i.code_rh                                 code_rh,
         i.nom_usuel || ' ' || i.prenom            intervenant_nom_prenom,
         i.date_naissance                          date_naissance,
         COALESCE(i.email_perso,d.email_perso)     email_perso,
         str.libelle_court                         composante_service,
         tm.libelle                                type_mission,
         m.libelle_mission                         libelle,
         m.date_debut                              date_debut,
         m.date_fin                                date_fin,
         CASE evh.code
           WHEN 'saisi' THEN tblm.heures_prevues_saisies
           ELSE tblm.heures_prevues_validees
           END                                       heures,
         tr.libelle                                taux_remu,
         trm.libelle                               taux_remu_majore,
         m.heures_formation                        heures_formation,
         vhm.id vhm_id,
         to_char(vhm.horaire_debut, 'YYYY-MM')     mois_realisation,
         vhm.heures                                heures_realisees,
         CASE WHEN vhm.auto_validation = 1 OR max(v.id) over (partition by vhm.id) IS NOT NULL THEN 1 ELSE 0 END valide,
         vhm.auto_validation,
         max(v.id) over (partition by vhm.id) validation_id,
         row_number() OVER (PARTITION BY vhm.id, evh.id ORDER BY vhm.id) r
       FROM
                     mission                        m
                JOIN type_volume_horaire          tvh ON tvh.code = 'PREVU'
                JOIN type_volume_horaire         tvhr ON tvhr.code = 'REALISE'
                JOIN etat_volume_horaire          evh ON 1=1
                JOIN intervenant                    i ON i.id = m.intervenant_id
                JOIN annee                          a ON a.id = i.annee_id
                JOIN structure                    str ON str.id = m.structure_id
                JOIN type_mission                  tm ON tm.id = m.type_mission_id
                JOIN tbl_mission                 tblm ON tblm.mission_id = m.id
           LEFT JOIN structure                   istr ON istr.id = i.structure_id
           LEFT JOIN volume_horaire_mission       vhm ON vhm.mission_id = m.id AND vhm.type_volume_horaire_id = tvhr.id AND vhm.histo_destruction IS NULL
           LEFT JOIN intervenant_dossier            d ON d.intervenant_id = i.id AND d.histo_destruction IS NULL
           LEFT JOIN taux_remu                     tr ON tr.id = COALESCE(m.taux_remu_id, tm.taux_remu_id)
           LEFT JOIN taux_remu                    trm ON trm.id = COALESCE(m.taux_remu_majore_id, m.taux_remu_id, tm.taux_remu_majore_id, tm.taux_remu_id)
           LEFT JOIN validation_vol_horaire_miss vvhm ON vvhm.volume_horaire_mission_id = vhm.id
           LEFT JOIN validation                     v ON v.id = vvhm.validation_id AND v.histo_destruction IS NULL
       WHERE
         m.histo_destruction IS NULL
     ) t
WHERE
  r = 1
GROUP BY
  annee_id,
  intervenant_id,
  mission_id,
  type_volume_horaire_id,
  etat_volume_horaire_id,
  structure_aff_id,
  structure_aff_ids,
  structure_ens_id,
  structure_ens_ids,

  type_etat,
  date_extraction,
  annee,
  code_intervenant,
  code_rh,
  intervenant_nom_prenom,
  date_naissance,
  email_perso,
  composante_service,
  type_mission,
  libelle,
  date_debut,
  date_fin,
  heures,
  taux_remu,
  taux_remu_majore,
  heures_formation,
  mois_realisation
ORDER BY
  intervenant_nom_prenom, mois_realisation