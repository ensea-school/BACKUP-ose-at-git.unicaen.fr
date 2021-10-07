CREATE OR REPLACE FORCE VIEW V_EXPORT_DMEP AS
WITH mep AS (
  SELECT
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id,

    sum(case when th.code = 'fi' then mep.heures else 0 end) heures_fi,
    sum(case when th.code = 'fa' then mep.heures else 0 end) heures_fa,
    sum(case when th.code = 'fc' then mep.heures else 0 end) heures_fc,
    sum(case when th.code = 'fc_majorees' then mep.heures else 0 end) heures_fc_majorees,
    sum(case when th.code = 'referentiel' then mep.heures else 0 end) heures_referentiel
  FROM
              mise_en_paiement              mep
         JOIN type_heures                    th ON th.id   = mep.type_heures_id
    LEFT JOIN formule_resultat_service      frs ON frs.id  = mep.formule_res_service_id
    LEFT JOIN formule_resultat_service_ref frsr ON frsr.id = mep.formule_res_service_ref_id
  WHERE
    mep.histo_destruction IS NULL
  GROUP BY
    frs.service_id,
    frsr.service_referentiel_id,
    mep.date_mise_en_paiement,
    mep.periode_paiement_id,
    mep.centre_cout_id,
    mep.domaine_fonctionnel_id
)
SELECT
  i.id                            intervenant_id,
  ti.id                           type_intervenant_id,
  i.annee_id                      annee_id,
  saff.id                         structure_aff_id,
  sens.id                         structure_ens_id,
  NVL(sens.id,saff.id)            structure_id,
  cc.id                           centre_cout_id,
  ep.id                           element_pedagogique_id,
  etp.id                          etape_id,
  tf.id                           type_formation_id,
  gtf.id                          groupe_type_formation_id,
  si.id                           statut_intervenant_id,
  p.id                            periode_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  i.date_naissance                intervenant_date_naissance,
  si.libelle                      intervenant_statut_libelle,
  ti.code                         intervenant_type_code,
  ti.libelle                      intervenant_type_libelle,
  g.source_code                   intervenant_grade_code,
  g.libelle_court                 intervenant_grade_libelle,
  di.source_code                  intervenant_discipline_code,
  di.libelle_court                intervenant_discipline_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  gtf.libelle_court               groupe_type_formation_libelle,
  tf.libelle_court                type_formation_libelle,
  etp.niveau                      etape_niveau,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  de.source_code                  element_discipline_code,
  de.libelle_court                element_discipline_libelle,
  fr.libelle_long                 fonction_referentiel_libelle,
  ep.taux_fi                      element_taux_fi,
  ep.taux_fc                      element_taux_fc,
  ep.taux_fa                      element_taux_fa,
  src.libelle                     element_source_libelle,
  COALESCE(to_char(s.description),to_char(sr.commentaires)) commentaires,

  CASE
    WHEN mep.date_mise_en_paiement IS NULL THEN 'a-mettre-en-paiement'
    ELSE 'mis-en-paiement'
  END                             etat,
  tr.libelle                      type_ressource_libelle,
  cc.source_code                  centre_couts_code,
  cc.libelle                      centre_couts_libelle,
  df.source_code                  domaine_fonctionnel_code,
  df.libelle                      domaine_fonctionnel_libelle,
  p.libelle_long                  periode_libelle,
  mep.date_mise_en_paiement       date_mise_en_paiement,
  mep.heures_fi                   heures_fi,
  mep.heures_fa                   heures_fa,
  mep.heures_fc                   heures_fc,
  mep.heures_fc_majorees          heures_fc_majorees,
  mep.heures_referentiel          heures_referentiel
FROM
              mep
         JOIN centre_cout               cc ON cc.id   = mep.centre_cout_id
         JOIN type_ressource            tr ON tr.id   = cc.type_ressource_id
    LEFT JOIN service                    s ON s.id    = mep.service_id
    LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
    LEFT JOIN source                   src ON src.id  = ep.source_id OR (ep.source_id IS NULL AND src.code = 'OSE')
    LEFT JOIN discipline                de ON de.id   = ep.discipline_id
    LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
    LEFT JOIN type_formation            tf ON tf.id   = etp.type_formation_id
    LEFT JOIN groupe_type_formation    gtf ON gtf.id  = tf.groupe_id
    LEFT JOIN service_referentiel       sr ON sr.id   = mep.service_referentiel_id
    LEFT JOIN fonction_referentiel      fr ON fr.id   = sr.fonction_id
         JOIN intervenant                i ON i.id    = NVL( s.intervenant_id, sr.intervenant_id )
         JOIN statut_intervenant        si ON si.id   = i.statut_id
         JOIN type_intervenant          ti ON ti.id   = si.type_intervenant_id
    LEFT JOIN grade                      g ON g.id    = i.grade_id
    LEFT JOIN discipline                di ON di.id   = i.discipline_id
    LEFT JOIN structure               saff ON saff.id = i.structure_id-- AND ti.code = 'P'
    LEFT JOIN structure               sens ON sens.id = NVL( ep.structure_id, sr.structure_id )
         JOIN etablissement           etab ON etab.id = NVL( s.etablissement_id, ose_parametre.get_etablissement() )
    LEFT JOIN periode                    p ON p.id    = mep.periode_paiement_id
    LEFT JOIN domaine_fonctionnel       df ON df.id   = mep.domaine_fonctionnel_id
ORDER BY
  intervenant_nom,
  service_structure_aff_libelle,
  service_structure_ens_libelle,
  etape_libelle,
  element_libelle