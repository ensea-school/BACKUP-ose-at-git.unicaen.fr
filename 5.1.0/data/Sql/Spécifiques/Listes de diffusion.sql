select * from v_sympa_int_permanents_2015 where email like '%deni%';
select * from v_sympa_int_vacataires_2015 where email like '%lecluse%';



SELECT DISTINCT
  email
FROM
  intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
  JOIN type_intervenant ti ON ti.id = si.type_intervenant_id
  JOIN service_referentiel sr ON sr.intervenant_id = i.id AND 1 = ose_divers.comprise_entre(sr.histo_creation,sr.histo_destruction)
  JOIN volume_horaire_ref vhr ON vhr.service_referentiel_id = sr.id AND 1 = ose_divers.comprise_entre(vhr.histo_creation,vhr.histo_destruction)
WHERE
  ti.code = 'P'
  AND 1 = ose_divers.comprise_entre( i.histo_creation, i.histo_destruction )
  AND i.annee_id = 2015
GROUP BY
  email
HAVING
  sum(vhr.heures) > 0
  
MINUS

select * from v_sympa_int_permanents_2015;