begin

  OSE_FORMULE.calculer( 553, 2014 );
  
--  OSE_FORMULE.CALCULER_SUR_DEMANDE;
  commit;
end;

/

select to_char(sysdate, 'DD/MM/YYYY HH24:MI:SS') from dual;

select id from intervenant where source_code = '18009'; -- 553

select * from v_formule_volume_horaire where INTERVENANT_ID = 553;


select '01. MAJ identifiée' descr, (select intervenant_id from formule_resultat_maj where intervenant_id = 553) val, to_char(sysdate,'DD/MM/YYYY HH:MM:SS') do from dual

union 
select '04. Résultat formule (nb heures réelles)' descr, service val, to_char(ose_formule.get_date_obs,'DD/MM/YYYY HH:MM:SS') do from formule_resultat where intervenant_id = 553

union
select '03. Heures de service (à partir de la vue)' descr, sum(heures) val, to_char(ose_formule.get_date_obs,'DD/MM/YYYY HH:MM:SS') do from V_FORMULE_VOLUME_HORAIRE where intervenant_id = 553

union
select '02. Heures de service (requête directe)' descr, sum(vh.heures) val, to_char(sysdate,'DD/MM/YYYY HH:MM:SS') do from volume_horaire vh
join service s on s.id = vh.service_id
where
  s.intervenant_id = 553
  and vh.histo_destruction is null
  and s.histo_destruction is null

order by descr;



select
  i.id i_id,
  s.id s_id,
  vh.ID vh_id, 
  epp.libelle_long periode_ep,
  TVH.LIBELLE type_volume_horaire,
  p.libelle_long periode,
  TI.CODE type_intervention,
  vh.heures,
  vh.motif_non_paiement_id,
  vh.contrat_id,
  vvh.validation_id,
  vh.histo_destruction vh_histo,
  s.histo_destruction s_histo,
  ep.histo_destruction ep_histo,
  v.histo_destruction v_histo
from
  volume_horaire vh
  JOIN service s ON s.id = vh.service_id
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN type_volume_horaire tvh on tvh.id = vh.TYPE_VOLUME_HORAIRE_ID
  JOIN periode p on p.id = vh.periode_id
  JOIN type_intervention ti on ti.id = vh.type_intervention_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
  LEFT JOIN VALIDATION_VOL_HORAIRE vvh on VVH.VOLUME_HORAIRE_ID = vh.id
  LEFT JOIN validation v ON v.id = VVH.VALIDATION_ID
  LEFT JOIN periode epp on epp.id = ep.periode_id
where
  i.source_code = '18009'
  AND vh.histo_destruction IS NULL
  --AND ti.code = 'TP'
order by
  s_id, type_volume_horaire, periode, TI.ORDRE;
  
  
  
  
  
  
  
select
  i.id, i.source_code
from
  intervenant i
  join statut_intervenant si on si.id = i.statut_id
where
  si.peut_saisir_service = 1
  AND not exists( select 1 from service where intervenant_id = i.id )
  AND not exists( select 1 from service_referentiel where intervenant_id = i.id )