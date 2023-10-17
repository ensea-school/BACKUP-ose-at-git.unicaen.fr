CREATE OR REPLACE FORCE VIEW V_VOL_HORAIRE_REF_ETAT_MULTI AS
select vh.id VOLUME_HORAIRE_REF_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire_ref vh
  join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
  join etat_volume_horaire evh on evh.code = 'saisi'
  where vh.histo_destruction IS NULL
union all
  select vh.id VOLUME_HORAIRE_REF_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire_ref vh
  join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
  join etat_volume_horaire evh on evh.code = 'valide'
  where vh.histo_destruction IS NULL
  and vh.auto_validation=1 OR EXISTS(
    SELECT * FROM validation v JOIN validation_vol_horaire_ref vvh ON vvh.validation_id = v.id
    WHERE vvh.volume_horaire_ref_id = vh.id AND v.histo_destruction IS NULL
  )
union all
  select vh.id VOLUME_HORAIRE_REF_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire_ref vh
  join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
  join contrat c on vh.contrat_id = c.id and c.histo_destruction IS NULL
  join etat_volume_horaire evh on evh.code = 'contrat-edite'
  where vh.histo_destruction IS NULL
union all
  select vh.id VOLUME_HORAIRE_REF_ID, evh.id ETAT_VOLUME_HORAIRE_ID
  from volume_horaire_ref vh
  join service_referentiel s on s.id = vh.service_referentiel_id and s.histo_destruction IS NULL
  join contrat c on vh.contrat_id = c.id and c.histo_destruction IS NULL and c.date_retour_signe is not null
  join etat_volume_horaire evh on evh.code = 'contrat-signe'
  where vh.histo_destruction IS NULL