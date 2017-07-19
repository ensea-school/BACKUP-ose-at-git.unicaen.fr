


-- paiements
select * from mise_en_paiement
--;delete from mise_en_paiement
--;update mise_en_paiement set histo_destructeur_id = 4, histo_destruction=sysdate
where formule_res_service_id IN (
  select id from formule_resultat_service where service_id IN (
    select id from service where intervenant_id IN (
      select id from intervenant where source_code = '99322'
    )
  )
);

-- volumes horaires de contrats
SELECT * from volume_horaire
--;update volume_horaire set contrat_id = NULL
WHERE contrat_id IN (
  select id from contrat
  --;delete from contrat
  --;update contrat set histo_destructeur_id = 4, histo_destruction=sysdate
  where intervenant_id IN (
    select id from intervenant where source_code = '99322'
  )
);

-- contrats
select * from contrat
--;delete from contrat
--;update contrat set histo_destructeur_id = 4, histo_destruction=sysdate
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);


-- validations de volumes_horaires
select * from validation_vol_horaire
--;delete from validation_vol_horaire
where volume_horaire_id IN (
  select id FROM volume_horaire WHERE service_id IN (
    select id from service where intervenant_id IN (
      select id from intervenant where source_code = '99322'
    )
  )
);


-- volumes horaires
select * from volume_horaire
--;delete from volume_horaire
--;update volume_horaire set histo_destructeur_id = 4, histo_destruction=sysdate
where service_id IN (
  select id from service where intervenant_id IN (
    select id from intervenant where source_code = '99322'
  )
);


-- services
select * from service
--;delete from service
--;update service set histo_destructeur_id = 4, histo_destruction=sysdate
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);


-- agrement du conseil restreint
select * from agrement
--;delete from agrement
--;update agrement set histo_destructeur_id = 4, histo_destruction=sysdate
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);


-- validations
select * from validation
--;delete from validation
--;update validation set histo_destructeur_id = 4, histo_destruction=sysdate
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);

-- pieces justificatives
select * from piece_jointe
--;delete from piece_jointe
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);

-- dossier
select * from dossier
--;delete from dossier
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);

-- workflow
select * from tbl_workflow
--;delete from tbl_workflow
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);

select * from indic_modif_dossier
--;delete from indic_modif_dossier
where intervenant_id IN (
  select id from intervenant where source_code = '99322'
);



-- intervenant
select * from intervenant
--;delete from intervenant
--;update intervenant set histo_destructeur_id = 4, histo_destruction=sysdate
where source_code = '99322';