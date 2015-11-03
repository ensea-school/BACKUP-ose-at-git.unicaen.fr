SELECT
  mep.id mep_id,
  i.id i_id, i.prenom || ' ' || i.nom_usuel intervenant,
  s.id s_id, s.libelle_court structure,
  p.id p_id, p.libelle_court periode_paiement,
  cc.id cc_id, cc.source_code centre_cout,
  th.id th_id, th.libelle_court type_heures,
  ep.source_code,
  mep.heures,
  mep.DATE_MISE_EN_PAIEMENT,
  to_char(mep.histo_creation, 'DD/MM/YYYY HH:MI:SS') mep_histo_creation,
  mep.histo_createur_id,
  mep.histo_modification,
  mep.histo_destructeur_id
FROM
  v_mep_intervenant_structure  mis
  JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id
  LEFT JOIN periode p on p.id = mep.periode_paiement_id
  JOIN centre_cout cc ON cc.id = mep.centre_cout_id
  JOIN type_heures th ON th.id = mep.type_heures_id
  JOIN intervenant i on i.id = mis.intervenant_id
  JOIN structure s on s.id = mis.structure_id
  LEFT JOIN FORMULE_RESULTAT_SERVICE frs ON frs.id = MEP.FORMULE_RES_SERVICE_ID
  LEFT JOIN service s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  --1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
  --AND i.source_code = '21472'
  --AND mep.histo_modificateur_id=2504
  i.source_code = '74315'
 -- AND s.id = 372
 -- AND to_char(mep.histo_modification,'YYYY-MM-DD') = to_char(SYSDATE,'YYYY-MM-DD')
;


--delete from mise_en_paiement where id in ();

--update mise_en_paiement set heures = 1.38 WHERE id = 446;

--update mise_en_paiement set histo_destruction = sysdate, histo_destructeur_id = 4 where id = 20499;

update mise_en_paiement set periode_paiement_id = 20, date_mise_en_paiement = to_date( '31/12/2015', 'DD/MM/YYYY') WHERE
id in (

22561,
22560

);






SELECT
  *
FROM 

(
WITH sp AS (
  SELECT
    mep.formule_res_service_id,
    SUM( CASE WHEN th.code = 'fi' THEN mep.heures ELSE 0 END ) payees_fi,
    SUM( CASE WHEN th.code = 'fa' THEN mep.heures ELSE 0 END ) payees_fa,
    SUM( CASE WHEN th.code IN ('fc','fc_majorees') THEN mep.heures ELSE 0 END ) payees_fc
  FROM
    mise_en_paiement mep
    JOIN type_heures th on th.id = mep.type_heures_id
  WHERE
    1 = ose_divers.comprise_entre(mep.histo_creation,mep.histo_destruction)
  GROUP BY
    mep.formule_res_service_id
)
SELECT
  i.nom_usuel || i.prenom i_nom,
  i.source_code i_code, 
  ep.source_code elmt,
  frs.heures_compl_fi, payees_fi,
  frs.heures_compl_fa, payees_fa,
  frs.heures_compl_fc, payees_fc
FROM
  formule_resultat_service frs
  JOIN formule_resultat fr on fr.id = frs.formule_resultat_id
  JOIN sp ON sp.formule_res_service_id = frs.id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN service s on s.id = frs.service_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  frs.heures_compl_fi < sp.payees_fi
  OR frs.heures_compl_fa < sp.payees_fa
  OR frs.heures_compl_fc < sp.payees_fc
ORDER BY
  i_nom, elmt
  
) t1
  
  
