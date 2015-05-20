/**************************************************************************************************
    Liste des PJ attendues pour un intervenant.
  
    pj.obligatoire = 1 <=> PJ obligatoire 
    pj.obligatoire = 0 <=> PJ facultative

    pj.force = 1 <=> Forçage, nécessaire pour rendre obligatoire la PJ RIB lorsque le numéro saisi 
                     dans le dossier diffère de celui importé.

 *******************************************************************************************************/

select pj.id pj_id, d.id dossier_id, d.intervenant_id, d.nom_usuel, 
    tpj.id pj_id, tpj.code, tpj.libelle, pj.obligatoire, pj.force, pj.histo_modification, pj.histo_destruction
from piece_jointe pj
join type_piece_jointe tpj on tpj.id = pj.type_piece_jointe_id
join dossier d on d.id = pj.dossier_id
join intervenant i on i.id = d.intervenant_id
where i.source_code = '38272'
--and pj.histo_destruction is null
order by pj.histo_modification, pj.histo_destruction;