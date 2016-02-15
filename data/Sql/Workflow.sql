-- etapes du workflow pour lesquelles une vue TBL doit être créée
select 
  * 
from
  wf_etape
where
  annee_id = 2015
  AND code NOT IN (
    'DEBUT',
    'DONNEES_PERSO_SAISIE',
    'DONNEES_PERSO_VALIDATION',
    
    'PJ_SAISIE',
    'PJ_VALIDATION',
    
    'CONSEIL_RESTREINT',
    'CONSEIL_ACADEMIQUE',
    
    'DEMANDE_MEP',
    'SAISIE_MEP',
    
    'FIN'
  )
order by ordre;


/**
 * Consultation de la Feuille de route d'un intervenant.
 */

select i.id, i.source_code, i.nom_usuel, e.ordre, e.code, e.libelle, ie.atteignable, ie.franchie, ie.courante, s.libelle_court
from wf_intervenant_etape ie 
join intervenant i on i.id = ie.intervenant_id
join wf_etape e on e.id = ie.etape_id
left join structure s on s.id = ie.structure_id
where 
  i.id = 517
  --and ie.structure_id is  null
  
order by e.ordre;





/**
 * Regénération de la Feuille de route d'un intervenant.
 */

begin    
  DBMS_OUTPUT.ENABLE;

  --  ose_workflow.update_intervenant_etapes(517);
    ose_workflow.update_all_intervenants_etapes(2015);
end;
/

