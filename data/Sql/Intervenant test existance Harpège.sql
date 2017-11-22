SELECT * FROM
(
  SELECT
    'contrat'                                          categorie,
    ca.no_dossier_pers                                 code,
    ct.c_type_contrat_trav                             contrat,
    null                                               type_population,
    ca.d_deb_contrat_trav                              date_deb, 
    COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav) date_fin,
    CASE WHEN
      SYSDATE BETWEEN ca.d_deb_contrat_trav-1 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+1
    THEN 1 ELSE 0 END                                                actuel
  FROM
    contrat_avenant@harpprod ca
    JOIN contrat_travail@harpprod ct ON ct.no_dossier_pers = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
  WHERE
    SYSDATE BETWEEN ca.d_deb_contrat_trav-184 AND COALESCE(ca.d_fin_execution,ca.d_fin_contrat_trav,SYSDATE)+184
  
  UNION ALL
  
  SELECT
    'affectation'                          categorie,
    a.no_dossier_pers                      code,
    null                                   contrat,
    c.c_type_population                    type_population,
    a.d_deb_affectation                    date_deb, 
    a.d_fin_affectation                    date_fin,
    CASE WHEN
      SYSDATE BETWEEN a.d_deb_affectation-1 AND COALESCE(a.d_fin_affectation,SYSDATE)+1
    THEN 1 ELSE 0 END                                                actuel
  FROM
    affectation@harpprod a
    LEFT JOIN carriere@harpprod c ON c.no_dossier_pers = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
  WHERE
    SYSDATE BETWEEN a.d_deb_affectation-184 AND COALESCE(a.d_fin_affectation,SYSDATE)+184
    
  UNION ALL
    
  SELECT
    'chercheur'                          categorie,
    ch.no_individu                       code,
    null                                 contrat,
    null                                 type_population,
    ch.d_deb_str_trav                    date_deb, 
    ch.d_fin_str_trav                    date_fin,
    CASE WHEN
      SYSDATE BETWEEN ch.d_deb_str_trav-1 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+1
    THEN 1 ELSE 0 END                                                actuel
  FROM
    chercheur@harpprod ch 
  WHERE 
    SYSDATE BETWEEN ch.d_deb_str_trav-184 AND COALESCE(ch.d_fin_str_trav,SYSDATE)+184
) i
WHERE
  1=1
  AND i.code = 103451
  