CREATE OR REPLACE FORCE VIEW V_MEP_INTERVENANT_STRUCTURE AS
SELECT
  rownum id,
  t1."MISE_EN_PAIEMENT_ID",
  t1."INTERVENANT_ID",
  t1."STRUCTURE_ID",
  t1.periode_paiement_id,
  t1.domaine_fonctionnel_id
FROM (

SELECT
  mep.id                   mise_en_paiement_id,
  fr.intervenant_id        intervenant_id,
  sr.structure_id          structure_id,
  mep.periode_paiement_id  periode_paiement_id,
  COALESCE(mep.domaine_fonctionnel_id, fr.domaine_fonctionnel_id) domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN formule_resultat_service_ref frsr ON frsr.formule_resultat_id = fr.id
  JOIN mise_en_paiement              mep ON mep.formule_res_service_ref_id = frsr.id
  JOIN centre_cout                    cc ON cc.id = mep.centre_cout_id
  JOIN service_referentiel            sr ON sr.id = frsr.service_referentiel_id
  JOIN fonction_referentiel           fr ON fr.id = sr.fonction_id
UNION

SELECT
  mep.id                                      mise_en_paiement_id,
  fr.intervenant_id                           intervenant_id,
  COALESCE( ep.structure_id, i.structure_id ) structure_id,
  mep.periode_paiement_id                     periode_paiement_id,
  COALESCE(
    mep.domaine_fonctionnel_id,
    e.domaine_fonctionnel_id,
    to_number((SELECT valeur FROM parametre WHERE nom = 'domaine_fonctionnel_ens_ext'))
  ) domaine_fonctionnel_id
FROM
  formule_resultat fr
  JOIN intervenant                       i ON i.id = fr.intervenant_id
  JOIN formule_resultat_service        frs ON frs.formule_resultat_id = fr.id
  JOIN mise_en_paiement                mep ON mep.formule_res_service_id = frs.id
  JOIN centre_cout                      cc ON cc.id = mep.centre_cout_id
  JOIN service                           s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique         ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN etape                        e ON e.id = ep.etape_id
) t1