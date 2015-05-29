/
SET SERVEROUTPUT ON;
/
begin
 -- UNICAEN_OSE_FORMULE.DEBUG_RESULTAT_V2( 548, 2014, 1, 1, 104 );

  ose_formule.calculer_tout;
end;
/

select * from intervenant where id = 528;

SELECT * FROM formule_resultat WHERE intervenant_id = (select id from intervenant where source_code = '18009');


update service set histo_destruction = sysdate, histo_destructeur_id = 1 where id = 16425;

SELECT 
  i.annee_id,
  i.id i_id,
  i.source_code i_code,
  i.nom_usuel || ' ' || i.prenom i_nom,
  fr.type_volume_horaire_id,
  fr.etat_volume_horaire_id,
  frs.service_id,
  frs.id frs_id
FROM
  formule_resultat_service frs
  JOIN formule_resultat fr ON fr.id = FRS.FORMULE_RESULTAT_ID
  JOIN intervenant i ON i.id = fr.intervenant_id
WHERE
  i.id = 630

;
  
select
  i_id, i_code, i_nom, round(hetd,2), round(sum(hetd_vh),2), count(*)
from (SELECT 
  fr.annee_id annee_id,
  i.id i_id,
  i.source_code i_code,
  i.nom_usuel || ' ' || i.prenom i_nom,
  fr.type_volume_horaire_id,
  fr.etat_volume_horaire_id,
  fr.service_assure-fr.referentiel   hetd,
  vh.service_id,
  frvh.volume_horaire_id,
  frvh.service_assure  hetd_vh,
  vh.heures
FROM
  formule_resultat_vh frvh
  JOIN formule_resultat fr ON fr.id = frvh.FORMULE_RESULTAT_ID
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN volume_horaire vh ON vh.id = frvh.volume_horaire_id
  JOIN v_volume_horaire_etat         vhe ON vhe.volume_horaire_id = vh.id AND vhe.etat_volume_horaire_id >= fr.etat_volume_horaire_id
where
  fr.etat_volume_horaire_id = 1
) t1
group by
  i_id, i_code, i_nom, hetd
having round(hetd,10) <> round(sum(hetd_vh),10)