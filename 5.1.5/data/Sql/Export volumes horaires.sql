
--  CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_EXPORT" ("SERVICE_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ID", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "INTERVENANT_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "INTERVENANT_CODE", "INTERVENANT_NOM", "INTERVENANT_STATUT_LIBELLE", "INTERVENANT_TYPE_LIBELLE", "SERVICE_STRUCTURE_AFF_LIBELLE", "SERVICE_STRUCTURE_ENS_LIBELLE", "ETABLISSEMENT_LIBELLE", "ETAPE_CODE", "ETAPE_LIBELLE", "ELEMENT_CODE", "ELEMENT_LIBELLE", "COMMENTAIRES", "ELEMENT_PERIODE_LIBELLE", "ELEMENT_PONDERATION_COMPL", "ELEMENT_SOURCE_LIBELLE", "HEURES_SERVICE", "HEURES_REELLES", "HEURES_SERVICE_STATUTAIRE", "HEURES_SERVICE_DU_MODIFIE", "HEURES_ASSUREES", "HEURES_SOLDE", "HEURES_NON_PAYEES", "HEURES_REFERENTIEL") AS 
  SELECT
  s.id                            service_id,
  vh.id                           volume_horaire_id,
  s.annee_id                      annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  evh.id                          etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  saff.id                         structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,

  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  etab.libelle                    etablissement_libelle,
  etp.source_code                 etape_code,
  etp.libelle                     etape_libelle,
  ep.source_code                  element_code,
  ep.libelle                      element_libelle,
  null                            commentaires,
  p.libelle_court                 element_periode_libelle,
  CASE WHEN fs.ponderation_service_compl = 1 THEN NULL ELSE fs.ponderation_service_compl END element_ponderation_compl,
  src.libelle                     element_source_libelle,

  vh.type_intervention_id         type_intervention_id,

  vh.heures                       heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  frvh.service_assure             heures_assurees,
  fr.heures_solde                 heures_solde,
  0                               heures_referentiel

FROM
  formule_resultat_vh                frvh
  JOIN volume_horaire                  vh
  JOIN service                          s ON s.id = vh.service_id
  JOIN intervenant                      i ON i.id    = s.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant              si ON si.id   = i.statut_id            
  JOIN type_intervenant                ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                     sens ON sens.id = s.structure_ens_id
  JOIN etablissement                 etab ON etab.id = s.etablissement_id
  JOIN formule_resultat                fr ON fr.intervenant_id = i.id AND fr.annee_id = s.annee_id AND fr.type_volume_horaire_id = vh.type_volume_horaire_id
  JOIN etat_volume_horaire            evh ON evh.id  = fr.etat_volume_horaire_id
  LEFT JOIN structure                saff ON saff.id = s.structure_aff_id AND ti.code = 'P'
  LEFT JOIN element_pedagogique        ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN periode                     p ON p.id    = vh.periode_id
  LEFT JOIN source                    src ON src.id  = ep.source_id
  LEFT JOIN etape                     etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation  enf ON enf.etape_id = etp.id
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = s.annee_id
  LEFT JOIN v_formule_service          fs ON fs.id = s.id
  LEFT JOIN formule_resultat_vh      frvh ON frvh.volume_horaire_id = vh.id AND frvh.formule_resultat_id = fr.id
  
WHERE
  ose_divers.comprise_entre( s.histo_creation, s.histo_destruction ) = 1
  AND ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) = 1
;
UNION

SELECT
  -1                              service_id,
  null                            volume_horaire_id,
  sr.annee_id                     annee_id,
  fr.type_volume_horaire_id       type_volume_horaire_id,
  fr.etat_volume_horaire_id       etat_volume_horaire_id,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  -1                              niveau_formation_id,
  -1                              etape_id,
  -1                              element_pedagogique_id,


  i.source_code                   intervenant_code,
  i.nom_usuel || ' ' || i.prenom  intervenant_nom,
  si.libelle                      intervenant_statut_libelle,
  ti.libelle                      intervenant_type_libelle,
  saff.libelle_court              service_structure_aff_libelle,

  sens.libelle_court              service_structure_ens_libelle,
  null                            etablissement_libelle,
  null                            etape_code,
  null                            etape_libelle,
  fonc.code                       element_code,
  fonc.libelle_court              element_libelle,
  sr.commentaires                 commentaires,
  null                            element_periode_libelle,
  null                            element_ponderation_compl,
  src.libelle                     element_source_libelle,
  
  null                            type_intervention_id,

  sr.heures                       heures_reelles,
  si.service_statutaire           heures_service_statutaire,
  fsm.heures                      heures_service_du_modifie,
  frr.service_assure              heures_assurees,
  fr.heures_solde                 heures_solde,
  0                               heures_non_payees,
  sr.heures                       heures_referentiel  

FROM
  service_referentiel                 sr
  JOIN fonction_referentiel         fonc ON fonc.id = sr.fonction_id
  JOIN intervenant                     i ON i.id    = sr.intervenant_id AND ose_divers.comprise_entre(  i.histo_creation,  i.histo_destruction ) = 1
  JOIN statut_intervenant             si ON si.id   = i.statut_id            
  JOIN type_intervenant               ti ON ti.id   = si.type_intervenant_id 
  JOIN structure                    sens ON sens.id = sr.structure_id
  JOIN formule_resultat               fr ON fr.intervenant_id = i.id
  JOIN source                        src ON src.code = 'OSE'
  LEFT JOIN structure               saff ON saff.id = i.structure_id AND ti.code = 'P'
  LEFT JOIN v_formule_service_modifie fsm ON fsm.intervenant_id = i.id AND fsm.annee_id = fr.annee_id
  LEFT JOIN formule_resultat_referentiel frr ON frr.service_referentiel_id = sr.id AND frr.formule_resultat_id = fr.id
WHERE
  ose_divers.comprise_entre( sr.histo_creation, sr.histo_destruction ) = 1;
  
  
  
  
  
  
  
  
  

--  CREATE OR REPLACE FORCE VIEW "OSE"."V_TBL_SERVICE_EXPORT_VH" ("SERVICE_ID", "ANNEE_ID", "TYPE_VOLUME_HORAIRE_ID", "ETAT_VOLUME_HORAIRE_ORDRE", "TYPE_INTERVENANT_ID", "STRUCTURE_AFF_ID", "INTERVENANT_ID", "STRUCTURE_ENS_ID", "NIVEAU_FORMATION_ID", "ETAPE_ID", "ELEMENT_PEDAGOGIQUE_ID", "TYPE_INTERVENTION_ID", "HEURES") AS 
  SELECT
  vh.type_volume_horaire_id       type_volume_horaire_id,
  evh.ordre                       etat_volume_horaire_ordre,
  ti.id                           type_intervenant_id,
  CASE WHEN ti.code = 'E' THEN NULL ELSE saff.id END structure_aff_id,
  i.id                            intervenant_id,
  sens.id                         structure_ens_id,
  enf.niveau_formation_id         niveau_formation_id,
  etp.id                          etape_id,
  ep.id                           element_pedagogique_id,
  vh.type_intervention_id         type_intervention_id,
  vh.heures                       heures
FROM
  volume_horaire                      vh
  JOIN v_volume_horaire_etat         vhe ON vhe.volume_horaire_id = vh.id
  JOIN service                         s ON s.id    = vh.service_id AND ose_divers.comprise_entre( s.histo_creation, s.histo_destruction ) = 1
  JOIN intervenant                     i ON i.id    = s.intervenant_id AND ose_divers.comprise_entre( i.histo_creation, i.histo_destruction ) = 1
  JOIN type_intervenant               ti ON ti.id   = i.type_id
  JOIN structure                    saff ON saff.id = s.structure_aff_id
  JOIN structure                    sens ON sens.id = s.structure_ens_id
  JOIN etablissement                etab ON etab.id = s.etablissement_id
  JOIN etat_volume_horaire           evh ON evh.id  = vhe.etat_volume_horaire_id
  LEFT JOIN element_pedagogique       ep ON ep.id   = s.element_pedagogique_id
  LEFT JOIN etape                    etp ON etp.id  = ep.etape_id
  LEFT JOIN v_etape_niveau_formation enf ON enf.etape_id = etp.id
WHERE
  ose_divers.comprise_entre( vh.histo_creation, vh.histo_destruction ) = 1;



type_volume_horaire_id
etat_volume_horaire_id
etat_volume_horaire_ordre
type_intervenant_id
intervenant_id
niveau_formation_id
etape_id
element_pedagogique_id
structure_aff_id
structure_ens_id

INTERVENANT_CODE
INTERVENANT_NOM
INTERVENANT_STATUT_LIBELLE
INTERVENANT_TYPE_LIBELLE
SERVICE_STRUCTURE_AFF_LIBELLE
SERVICE_STRUCTURE_ENS_LIBELLE
ETABLISSEMENT_LIBELLE
ETAPE_CODE
ETAPE_LIBELLE
ELEMENT_CODE
ELEMENT_LIBELLE
ELEMENT_PERIODE_LIBELLE
ELEMENT_PONDERATION_COMPL
ELEMENT_SOURCE_LIBELLE
COMMENTAIRES

HEURES_SERVICE_STATUTAIRE
HEURES_SERVICE_DU_MODIFIE
HEURES_REELLES
HEURES_ASSUREES
HEURES_SOLDE
HEURES_NON_PAYEES
HEURES_SERVICE
HEURES_REFERENTIEL

SERVICE_ID,
TYPE_INTERVENTION_ID,
HEURES;



select
  vh.*
from
  volume_horaire vh
  --left join formule_resultat_vh frvh on frvh.volume_horaire_id = vh.id
WHERE
  vh.histo_destruction IS NULL AND vh.motif_non_paiement_id IS NULL

