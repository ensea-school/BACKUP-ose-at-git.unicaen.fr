CREATE OR REPLACE FORCE VIEW SRC_ELEMENT_TAUX_REGIMES AS 
WITH aetr AS (
  SELECT
    e.z_element_pedagogique_id  z_element_pedagogique_id,
    to_number(e.annee_id) annee_id,
    e.effectif_fi         effectif_fi,
    e.effectif_fc         effectif_fc,
    e.effectif_fa         effectif_fa
  FROM
    ose_element_effectifs@apoprod e
  WHERE
    (e.effectif_fi + e.effectif_fc + e.effectif_fa) > 0
)
SELECT
  ep.id                                element_pedagogique_id,
  ep.annee_id                          annee_id,
  OSE_DIVERS.CALCUL_TAUX_FI( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fi,
  OSE_DIVERS.CALCUL_TAUX_FC( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fc,
  OSE_DIVERS.CALCUL_TAUX_FA( COALESCE(aetr.effectif_fi,aetraa.effectif_fi), COALESCE(aetr.effectif_fc,aetraa.effectif_fc), COALESCE(aetr.effectif_fa,aetraa.effectif_fa), ep.fi, ep.fc, ep.fa ) taux_fa,
  s.id                                 source_id,
  ep.annee_id || '-' || ep.source_code source_code
FROM
  element_pedagogique ep
  JOIN source s ON s.code = 'Apogee'
  -- on récupère la période de paiement de l'année universitaire correspondant à la date du jour
  LEFT JOIN periode p ON p.code = OSE_DIVERS.DATE_TO_PERIODE_CODE(sysdate,ep.annee_id)
  -- taux de mixité depuis Apogée,
  -- si on est après le 1er décembre, donc que l'année universitaire est entammée depuis + de 2 mois,
  -- alors on prend l'année en cours, sinon on prend les effectifs de l'année antérieure
  LEFT JOIN aetr ON aetr.z_element_pedagogique_id = ep.source_code AND aetr.annee_id = ep.annee_id + CASE WHEN COALESCE(p.ecart_mois,0) > 2 THEN 0 ELSE -1 END
  -- on récupère dans tous les les effectifs de l'année n-1
  LEFT JOIN aetr aetraa ON aetraa.z_element_pedagogique_id = ep.source_code AND aetraa.annee_id = ep.annee_id - 1
  -- on récupère les taux de mixité saisis dans OSE, comme ça s'il y en a, on ne fera pas de synchro pour ne pas les écraser
  LEFT JOIN element_taux_regimes etr ON etr.element_pedagogique_id = ep.id AND etr.source_id <> s.id AND etr.histo_destruction IS NULL
WHERE
  -- les éléments pédagogiques doivent être actifs sinon pas de synchro
  ep.histo_destruction IS NULL
  -- il ne doit pas déjà y en avoir un de présent saisi direct dans OSE
  AND etr.id IS NULL
  -- pas d'import si pas d'effectifs n ou n-1
  AND (aetr.z_element_pedagogique_id IS NOT NULL OR aetraa.z_element_pedagogique_id IS NOT NULL)