SELECT
  s.id,
  i.id,
  s.structure_aff_id,
  i.structure_id,
  s.structure_ens_id,
  ep.structure_id
FROM
  service s
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  s.structure_aff_id <> i.structure_id
  OR s.structure_ens_id <> ep.structure_id;
  
  
UPDATE service SET
  structure_aff_id = (SELECT structure_id FROM intervenant WHERE intervenant.id = service.intervenant_id),
  structure_ens_id = (SELECT structure_id FROM element_pedagogique ep WHERE ep.id = service.element_pedagogique_id)
WHERE
  service.id IN (
SELECT
  s.id
FROM
  service s
  JOIN intervenant i ON i.id = s.intervenant_id
  JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  s.structure_aff_id <> i.structure_id
  OR s.structure_ens_id <> ep.structure_id );
  
/

alter trigger "OSE"."SERVICE_HISTO_CK" disable;
alter trigger "OSE"."SERVICE_CK" disable;

/

alter trigger "OSE"."SERVICE_HISTO_CK" enable;
alter trigger "OSE"."SERVICE_CK" enable;

/

select * from intervenant where nom_usuel like '%Martin%';