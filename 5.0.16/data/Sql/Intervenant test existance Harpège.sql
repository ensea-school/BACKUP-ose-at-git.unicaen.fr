WITH validite ( no_individu, fin ) AS (
  SELECT
    no_individu,
    CASE WHEN MAX( fin ) = to_date('12/12/9999','DD/MM/YYYY') THEN NULL ELSE MAX( fin ) END fin
  FROM
    (SELECT
      ch.no_individu no_individu,
      COALESCE( ch.d_fin_str_trav, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      chercheur@harpprod ch
    WHERE
      SYSDATE BETWEEN COALESCE(ch.d_deb_str_trav, SYSDATE) AND COALESCE(ch.d_fin_str_trav + 6*31, SYSDATE)
    UNION SELECT
      a.no_dossier_pers no_individu,
      COALESCE( a.d_fin_affectation, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      affectation@harpprod a
    WHERE
      SYSDATE BETWEEN COALESCE(a.d_deb_affectation, SYSDATE) AND COALESCE(a.d_fin_affectation + 6*31, SYSDATE)
    UNION SELECT
      ar.no_dossier_pers no_individu,
      COALESCE( ar.d_fin_affe_rech, to_date('12/12/9999','DD/MM/YYYY') ) fin
    FROM
      affectation_recherche@harpprod ar
    WHERE
      SYSDATE BETWEEN COALESCE(ar.d_deb_affe_rech, SYSDATE) AND COALESCE(ar.d_fin_affe_rech + 6*31, SYSDATE)
  )
  GROUP BY
    no_individu
)
SELECT DISTINCT
  initcap(individu.nom_usuel)                     nom_usuel,
  initcap(individu.nom_patronymique)              nom_patronymique,
  initcap(individu.prenom)                        prenom,
  si.source_code                                  statut_code,
  ltrim(TO_CHAR(individu.no_individu,'99999999')) code,
  CASE WHEN ch.no_individu IS NOT NULL AND SYSDATE BETWEEN COALESCE(ch.d_deb_str_trav, SYSDATE) AND COALESCE(ch.d_fin_str_trav + 6*31, SYSDATE) THEN 1 ELSE 0 END chercheur,
  ch.d_deb_str_trav chercheur_deb,
  ch.d_fin_str_trav chercheur_fin,
  
  CASE WHEN a.no_dossier_pers IS NOT NULL AND SYSDATE BETWEEN COALESCE(a.d_deb_affectation, SYSDATE) AND COALESCE(a.d_fin_affectation + 6*31, SYSDATE) THEN 1 ELSE 0 END affectation,
  a.d_deb_affectation affectation_deb,
  a.d_fin_affectation affectation_fin,
  
  CASE WHEN ar.no_dossier_pers IS NOT NULL AND SYSDATE BETWEEN COALESCE(ar.d_deb_affe_rech, SYSDATE) AND COALESCE(ar.d_fin_affe_rech + 6*31, SYSDATE) THEN 1 ELSE 0 END aff_rech,
  ar.d_deb_affe_rech aff_rech_deb,
  ar.d_fin_affe_rech aff_rech_fin
FROM
            individu@harpprod           individu
  LEFT JOIN                             validite           ON validite.no_individu           = individu.no_individu
  LEFT JOIN contrat_avenant@harpprod    ca                 ON ca.no_dossier_pers             = individu.no_individu AND 1 = ose_divers.comprise_entre( ca.d_deb_contrat_trav, NVL(ca.d_fin_execution,ca.d_fin_contrat_trav), TRUNC(validite.fin), 1 )
  LEFT JOIN contrat_travail@harpprod    ct                 ON ct.no_dossier_pers             = ca.no_dossier_pers AND ct.no_contrat_travail = ca.no_contrat_travail
  LEFT JOIN affectation@harpprod        a                  ON a.no_dossier_pers              = individu.no_individu AND 1 = ose_divers.comprise_entre( a.d_deb_affectation, a.d_fin_affectation, TRUNC(validite.fin), 1 )
  LEFT JOIN carriere@harpprod           c                  ON c.no_dossier_pers              = a.no_dossier_pers AND c.no_seq_carriere = a.no_seq_carriere
  LEFT JOIN statut_intervenant          si                 ON 1 = ose_divers.comprise_entre( si.histo_creation, si.histo_destruction ) AND si.source_code = CASE 
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('MC','MA')                THEN 'ASS_MI_TPS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('AT')                     THEN 'ATER'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('AX')                     THEN 'ATER_MI_TPS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('DO')                     THEN 'DOCTOR'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('GI','PN','ED')           THEN 'ENS_CONTRACT'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('LT','LB')                THEN 'LECTEUR'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('MB','MP')                THEN 'MAITRE_LANG'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('PT')                     THEN 'HOSPITALO_UNIV'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('C3','CA','CB','CD','CS','HA','HD','HS','MA','S3','SX','SW','SY','SZ','VA') THEN 'BIATSS'
         WHEN ca.no_dossier_pers IS NOT NULL AND ct.c_type_contrat_trav IN ('CU','AH','CG','MM','PM','IN','DN','ET') THEN 'NON_AUTORISE'

         WHEN c.c_type_population IN ('DA','OA','DC')                THEN 'ENS_2ND_DEG'
         WHEN c.c_type_population IN ('SA')                          THEN 'ENS_CH'
         WHEN c.c_type_population IN ('AA','AC','BA','IA','MA')      THEN 'BIATSS'
         WHEN c.c_type_population IN ('MG','SB')                     THEN 'HOSPITALO_UNIV'

                                                                     ELSE 'AUTRES' END
  LEFT JOIN chercheur@harpprod          ch                 ON ch.no_individu                 = individu.no_individu
  LEFT JOIN affectation_recherche@harpprod ar              ON ar.no_dossier_pers             = individu.no_individu
WHERE
  individu.no_individu = '44164'