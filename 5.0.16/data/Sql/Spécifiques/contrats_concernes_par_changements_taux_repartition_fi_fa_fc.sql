SELECT * FROM 
(
with etr as (

SELECT
  e.source_code z_element_pedagogique_id,
  to_number(e.annee_id) + 1 annee_id,
  effectif_fi,effectif_fa,effectif_fc,
  OSE_DIVERS.CALCUL_TAUX_FI( effectif_fi, effectif_fc, effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( effectif_fi, effectif_fc, effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( effectif_fi, effectif_fc, effectif_fa, ep.fi, ep.fc, ep.fa ) taux_fa,
  ose_import.get_source_id('Apogee') source_id,
  e.annee_id || '-' || e.source_code source_code
FROM
  ucbn_ose_element_effectifs@apoprod e
  JOIN element_pedagogique ep ON ep.source_code = e.source_code
WHERE
  (effectif_fi + effectif_fc + effectif_fa) > 0
  AND NOT EXISTS(
    SELECT * FROM element_taux_regimes etr JOIN element_pedagogique ep2 ON ep2.id = etr.element_pedagogique_id WHERE
      ep2.source_code = e.source_code
      AND etr.annee_id = to_number(e.annee_id) + 1
      AND etr.source_id <> ose_import.get_source_id('Apogee')
  )
)
  
  
select
  nvl(etr.z_element_pedagogique_id,mv.z_element_pedagogique_id) ep_code,
  nvl(etr.annee_id, mv.annee_id) annee_id,
  ep.fi, ep.fa,ep.fc,
  effectif_fi,effectif_fa,effectif_fc,
  etr.taux_fi nfi, etr.taux_fa nfa, etr.taux_fc nfc,
  mv.taux_fi ofi, mv.taux_fa ofa, mv.taux_fc ofc,
  (select count(*) from service s where element_pedagogique_id = ep.id AND 1 = ose_divers.comprise_entre(s.histo_creation,s.histo_destruction)) services,
  ose_divers.implode(
    'select distinct
      i.prenom || '' '' || i.nom_usuel || '' ('' || i.source_code || '')''
    from
      service s
      JOIN volume_horaire vh ON vh.service_id = s.id AND 1 = ose_divers.comprise_entre(vh.histo_creation,vh.histo_destruction)
      JOIN intervenant i ON i.id = s.intervenant_id
      JOIN contrat c ON c.id = vh.contrat_id
    where
      element_pedagogique_id = ' || ep.id || '
      AND 1 = ose_divers.comprise_entre(s.histo_creation,s.histo_destruction)
      AND c.validation_id IS NOT NULL',
  ', ') intervenants_avec_contrats
from
  etr
  FULL JOIN mv_element_taux_regimes mv ON mv.source_code = etr.source_code
  JOIN element_pedagogique ep ON ep.source_code = nvl(etr.z_element_pedagogique_id,mv.z_element_pedagogique_id)
WHERE
  nvl(etr.annee_id, mv.annee_id) = 2014
  AND (
    etr.taux_fi <> mv.taux_fi
    OR etr.taux_fc <> mv.taux_fc
    OR etr.taux_fa <> mv.taux_fa
  )
  
)t1

WHERE
  services > 0;
  
  
-- CONTROLE


select
  s.libelle, ep.source_code, ep.fi, ep.fc, ep.fa, ep.taux_fi, ep.taux_fc, ep.taux_fa
from
  element_pedagogique ep
  join source s on s.id = ep.source_id
where
  1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
  AND(
    (fi = 0 and taux_fi > 0)
    or (fc = 0 and taux_fc > 0)
    or (fa = 0 and taux_fa > 0)
  )
;


select * from element_pedagogique where fi + fa + fc = 0;
update element_pedagogique set fi = 1 where fi + fa + fc = 0;
update element_pedagogique ep set taux_fi = 0, taux_fc=1 where 1 = ose_divers.comprise_entre(ep.histo_creation,ep.histo_destruction)
  AND(
    (fi = 0 and taux_fi > 0)
    or (fc = 0 and taux_fc > 0)
    or (fa = 0 and taux_fa > 0)
  );
