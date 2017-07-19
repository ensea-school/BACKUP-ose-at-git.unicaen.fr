SELECT * FROM carriere@harpprod c WHERE c.no_dossier_pers = 18327;
SELECT * FROM contrat_travail@harpprod ct WHERE ct.no_dossier_pers = 18327;
SELECT * FROM contrat_avenant@harpprod ca WHERE ca.no_dossier_pers = 18327;

SELECT * FROM chercheur@harpprod ch WHERE ch.no_individu = 18327;
SELECT * FROM affectation@harpprod a WHERE a.no_dossier_pers = 18327;
SELECT * FROM affectation_recherche@harpprod ar WHERE ar.no_dossier_pers = 18327;
SELECT * FROM individu_fonct_struct@harpprod ifs WHERE ifs.no_dossier_pers = 18327;


-----------------------------------------------------------------------
-- Recherche de vacataires ayant le statut "Autres" et un persopass.
-----------------------------------------------------------------------

select i.nom_usuel, i.prenom, i.source_code, ucbn_ldap.hid2alias(i.source_code) login
from intervenant_exterieur ie 
join intervenant i on i.id = ie.id
left join dossier d on d.intervenant_id = ie.id
where d.id is null and i.annee_id = 2014 and i.statut_id = 19 
and ucbn_ldap.hid2alias(i.source_code) is not null
;