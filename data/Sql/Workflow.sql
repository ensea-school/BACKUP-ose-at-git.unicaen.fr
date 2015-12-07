/**
 * Consultation de la Feuille de route d'un intervenant.
 */

select i.id, i.source_code, i.nom_usuel, e.ordre, e.code, e.libelle, ie.atteignable, ie.franchie, ie.courante, s.libelle_court
from wf_intervenant_etape ie 
join intervenant i on i.id = ie.intervenant_id
join wf_etape e on e.id = ie.etape_id
left join structure s on s.id = ie.structure_id
where 
  i.source_code = '19010' 
  --and ie.structure_id is  null
  AND i.annee_id = 2015
order by e.ordre;


/**
 * Création d'une nouvelle étape.
 */

Insert into WF_ETAPE (ID, CODE, LIBELLE, ORDRE, STEP_CLASS, PERTIN_FUNC, FRANCH_FUNC, VISIBLE, STRUCTURE_DEPENDANT, STRUCTURES_IDS_FUNC) 
values (
    WF_ETAPE_id_seq.nextval, 
    'CLOTURE_REALISE', 
    'Clôture de la saisie des enseignements réalisés', 
    115,
    'Application\Service\Workflow\Step\ClotureRealiseStep', 
    'ose_workflow.peut_cloturer_realise', 
    'ose_workflow.realise_cloture', 
    '1', 
    '0', 
    null--'ose_workflow.fetch_struct_ensref_realis_ids'
);




/**
 * Regénération de la Feuille de route d'un intervenant.
 */

begin    
    --ose_workflow.update_intervenant_etapes(7992);
    ose_workflow.update_all_intervenants_etapes;
end;
/

