-- recherche d'intervenants
SELECT
  i.id || ',' id,
  srci.nom_patronymique, srci.nom_usuel, srci.prenom, srci.code code_harpege, si.libelle statut
FROM
  src_intervenant srci
  LEFT JOIN intervenant i ON i.source_code = srci.source_code AND i.annee_id = srci.annee_id
  JOIN statut_intervenant si ON si.id = srci.statut_id
WHERE
  srci.annee_id = 2017 -- pour 2017/2018
  --AND ose_divers.str_reduce(srci.nom_usuel || srci.nom_patronymique) LIKE '%fer%'
  --AND srci.code = '' -- Code Harpège
  AND srci.code IN (
80197,
47933,
34219,
123371,
132818,
132467,
20677,
47271,
132947,
47272,
121631,
30689,
132285,
20644,
30697,
30694,
104831,
122373,
20630,
62966,
79038,
30629,
104832,
20636,
30670,
33473,
20637,
105913,
20643,
109033,
132565,
122573,
62842,
122514,
123351,
105911,
121002,
121871,
132498,
45010,
48391,
20685,
20691,
30703

  )
;

/
-- pour importer des intervenants pas trouvés dans OSE
DECLARE 
  ANNEE_ID VARCHAR2(4) DEFAULT '2017';
  INTERVENANT_CODES VARCHAR2(4000) DEFAULT 
  /* liste des intervenants : codes harpège séparés par des virgules */ 
  '111911,20642,20665,20670,20674,69679,80260,93993'; 
BEGIN
  UNICAEN_IMPORT.SYNCHRONISATION('INTERVENANT', 
    'WHERE source_code IN (' || INTERVENANT_CODES || ') AND ANNEE_ID=' || ANNEE_ID
  );
END;
/


-- liste des statuts
SELECT
  id, libelle
FROM
  statut_intervenant si
WHERE
  si.histo_destruction IS NULL
;



SELECT
'INSERT INTO intervenant_saisie(id,intervenant_id,statut_id) VALUES (
  intervenant_saisie_id_seq.nextval, ' || intervenant_id || ', ' || statut_id || ');' isql FROM (
SELECT 
  31 statut_id, -- à personnaliser
  id intervenant_id FROM intervenant WHERE ID IN (
  -- liste d'ID d'intervenants à coller (avec des virgules entre chaque ID)
  -- Pour récupérer le résultat, cliquer sur la liste du résultat avec le bouton droit, puis 
  -- Exporter, format delimited, enregistrer sous "Presse papier"
  -- Puis, dans une nouvelle fenêtre SQL, coller le résultat et exécuter les requêtes
  -- Ne pas oublier de valider (commit), sinon ce n'est pas enrsgistré et ça peut bloquer la base.
  
33799,
35712,
35713,
35714,
35580,
35715,
35623,
35716,
35717,
35718,
35719,
35720,
35611,
35627,
35615,
35721,
35722,
35613,
35619,
29619,
35723,
35724,
35725,
35726,
35617,
35727,
35728,
35729,
35730,
35625,
35731,
35629,
35732,
35733,
35609,
35734,
20726,
35621,
35607,
35735,
35736,
35737,
19475,
35605
  
  ));