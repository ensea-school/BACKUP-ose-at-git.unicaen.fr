CREATE OR REPLACE FORCE VIEW V_FR_SERVICE_REF_CENTRE_COUT AS
SELECT
  frsr.id formule_resultat_serv_ref_id, cc.id
FROM
  formule_resultat_service_ref   frsr
  JOIN service_referentiel    sr ON sr.id = frsr.service_referentiel_id
  JOIN centre_cout            cc ON cc.histo_destruction IS NULL

  JOIN centre_cout_structure ccs ON ccs.centre_cout_id = cc.id
                                AND ccs.structure_id = sr.structure_id
                                AND ccs.histo_destruction IS NULL

  JOIN cc_activite             a ON a.id = cc.activite_id
                                AND a.histo_destruction IS NULL

  JOIN type_ressource         tr ON tr.id = cc.type_ressource_id
                                AND tr.histo_destruction IS NULL
WHERE
  frsr.heures_compl_referentiel > 0 AND tr.referentiel = 1