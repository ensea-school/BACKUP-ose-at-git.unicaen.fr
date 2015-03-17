SELECT
  mep.id mep_id,
  i.id i_id, i.prenom || ' ' || i.nom_usuel intervenant,
  s.id s_id, s.libelle_court structure,
  p.id p_id, p.libelle_court periode_paiement,
  cc.id cc_id, cc.source_code centre_cout,
  th.id th_id, th.libelle_court type_heures,
  ep.source_code,
  mep.heures
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
  mep.histo_destruction IS NULL
and i.id = 517;


--delete from mise_en_paiement where id in (29,31);

--update mise_en_paiement set histo_destruction = null, histo_destructeur_id = null where id = 44;