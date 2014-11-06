
  --CREATE OR REPLACE FORCE VIEW "OSE"."V_HARP_INTERVENANT_PERMANENT" AS 
WITH un_corps (corps_id) AS (

  SELECT
    c_corps z_corps_id
  FROM
    carriere@harpprod c
    JOIN element_carriere@harpprod e ON ( c.no_seq_carriere = e.no_seq_carriere and c.no_dossier_pers = e.no_dossier_pers)
  WHERE
    OSE_IMPORT.GET_DATE_OBS BETWEEN c.d_deb_carriere AND COALESCE(c.d_fin_carriere,OSE_IMPORT.GET_DATE_OBS)
    AND (OSE_IMPORT.GET_DATE_OBS BETWEEN e.d_effet_element AND COALESCE(e.D_FIN_ELEMENT,OSE_IMPORT.GET_DATE_OBS))
    AND e.D_ANNULATION is NULL
    AND e.TEM_PROVISOIRE = 'N'
    AND (c.NO_SEQ_CARRIERE IN (
        SELECT MAX(o.NO_SEQ_CARRIERE) FROM occupation@harpprod o WHERE o.no_dossier_pers = c.NO_DOSSIER_PERS
        AND (OSE_IMPORT.GET_DATE_OBS BETWEEN o.D_DEB_OCCUPATION AND COALESCE(o.D_FIN_OCCUPATION,OSE_IMPORT.GET_DATE_OBS))
      ) OR (
        SELECT MAX(o.NO_SEQ_CARRIERE) FROM occupation@harpprod o WHERE o.no_dossier_pers = c.NO_DOSSIER_PERS
        AND (OSE_IMPORT.GET_DATE_OBS BETWEEN o.D_DEB_OCCUPATION AND COALESCE(o.D_FIN_OCCUPATION,OSE_IMPORT.GET_DATE_OBS))
      ) IS NULL
    )

)
SELECT
  null id,--(SELECT id FROM intervenant WHERE intervenant.source_code = tmp.source_code) id, 
  source_id, 
  source_code, 
  null corps_id,--(SELECT id FROM corps WHERE corps.source_code = z_corps_id) corps_id,
  z_corps_id
FROM
(SELECT
  null                                              id,
  'Harpege'                                         source_id,
  ltrim(TO_CHAR(individu.no_individu,'99999999'))   source_code,
  chercheur.c_structure                             z_unite_recherche_id,
  (SELECT DISTINCT corps_id FROM un_corps) z_corps_id
FROM
  individu@harpprod individu
  LEFT JOIN chercheur@harpprod chercheur ON (
    chercheur.NO_INDIVIDU = individu.NO_INDIVIDU
    AND chercheur.d_deb_str_trav <= sysdate and (chercheur.d_fin_str_trav >= sysdate or chercheur.d_fin_str_trav is null)
  )
) tmp;



/*

Essais d'attributions :

BEGIN ose_import.set_date_obs(to_date('2013-07-01','YYYY-MM-DD')); END;
BEGIN ose_import.set_date_obs(SYSDATE); END;
BEGIN ose_import.set_date_obs(NULL); END;
*/
select OSE_IMPORT.GET_DATE_OBS from civilite;


--select no_dossier_pers_tmp, count(*) from (

SELECT
  OSE_IMPORT.GET_DATE_OBS,
  c.no_dossier_pers no_dossier_pers_tmp,
  e.no_seq_element,
  c_corps z_corps_id,
  c.*, e.*, o.*
FROM
  carriere@harpprod c
  JOIN element_carriere@harpprod e ON (c.no_dossier_pers = e.no_dossier_pers AND c.no_seq_carriere = e.no_seq_carriere)
  LEFT JOIN occupation@harpprod o ON (o.no_dossier_pers = c.no_dossier_pers AND o.no_seq_carriere = c.no_seq_carriere)
WHERE
  (OSE_IMPORT.GET_DATE_OBS IS NULL OR ( OSE_IMPORT.GET_DATE_OBS = OSE_IMPORT.GET_DATE_OBS
 --   AND OSE_IMPORT.GET_DATE_OBS BETWEEN c.d_deb_carriere AND COALESCE(c.d_fin_carriere,OSE_IMPORT.GET_DATE_OBS)
 --   AND OSE_IMPORT.GET_DATE_OBS BETWEEN e.d_effet_element AND COALESCE(e.D_FIN_ELEMENT,OSE_IMPORT.GET_DATE_OBS)
 --   AND OSE_IMPORT.GET_DATE_OBS BETWEEN o.D_DEB_OCCUPATION AND COALESCE(o.D_FIN_OCCUPATION,OSE_IMPORT.GET_DATE_OBS)
 --   AND OSE_IMPORT.GET_DATE_OBS < e.D_ANNULATION
  ))
  AND e.TEM_PROVISOIRE = 'N'

--) tmp group by tmp.no_dossier_pers_tmp having count(*) = 2
and c.no_dossier_pers = 947;


/*  AND (c.NO_SEQ_CARRIERE IN (
      SELECT o.NO_SEQ_CARRIERE FROM occupation@harpprod o WHERE o.no_dossier_pers = c.NO_DOSSIER_PERS
      AND (OSE_IMPORT.GET_DATE_OBS BETWEEN o.D_DEB_OCCUPATION AND COALESCE(o.D_FIN_OCCUPATION,OSE_IMPORT.GET_DATE_OBS))
    ) OR (
      SELECT o.NO_SEQ_CARRIERE FROM occupation@harpprod o WHERE o.no_dossier_pers = c.NO_DOSSIER_PERS
      AND (OSE_IMPORT.GET_DATE_OBS BETWEEN o.D_DEB_OCCUPATION AND COALESCE(o.D_FIN_OCCUPATION,OSE_IMPORT.GET_DATE_OBS))
    ) IS NULL
  )*/
 --AND c.no_dossier_pers = individu.no_individu








