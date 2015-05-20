/**
 * Consultation de la Feuille de route d'un intervenant.
 */

select i.nom_usuel, e.code, e.libelle, ie.franchie, ie.courante, s.libelle_court
from wf_intervenant_etape ie 
join intervenant i on i.id = ie.intervenant_id
join wf_etape e on e.id = ie.etape_id
left join structure s on s.id = ie.structure_id
where i.source_code = '1058';


/**
 * Regénération de la Feuille de route d'un intervenant.
 */

begin    
    ose_workflow.update_intervenant_etapes(1632);
end;
/

