SELECT
  mep.id || ',' /*
  mep.id mep_id,
  i.id i_id, i.prenom || ' ' || i.nom_usuel intervenant,
  s.id s_id, s.libelle_court structure,
  p.id p_id, p.libelle_court periode_paiement,
  cc.id cc_id, cc.source_code centre_cout,
  th.id th_id, th.libelle_court type_heures,
  ep.source_code,
  mep.heures,
  mep.DATE_MISE_EN_PAIEMENT,
  to_char(mep.histo_creation, 'DD/MM/YYYY HH:MI:SS') mep_histo_creation,
  mep.histo_createur_id,
  mep.histo_modification,
  mep.histo_destructeur_id*/
FROM
  v_mep_intervenant_structure  mis
  JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id
  LEFT JOIN periode p on p.id = mep.periode_paiement_id
  JOIN centre_cout cc ON cc.id = mep.centre_cout_id
  JOIN type_heures th ON th.id = mep.type_heures_id
  JOIN intervenant i on i.id = mis.intervenant_id
  JOIN structure s on s.id = mis.structure_id
  LEFT JOIN FORMULE_RESULTAT_SERVICE frs ON frs.id = MEP.FORMULE_RES_SERVICE_ID
  LEFT JOIN service s ON s.id = frs.service_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  --1 = ose_divers.comprise_entre( mep.histo_creation, mep.histo_destruction )
  --AND i.source_code = '21472'
  --AND mep.histo_modificateur_id=2504
  i.id IN (
  6167,
4834,
4978,
5404,
4968,
6184,
5747,
6322
  )
 -- AND s.id = 372
 -- AND to_char(mep.histo_modification,'YYYY-MM-DD') = to_char(SYSDATE,'YYYY-MM-DD')
;


select * from tbl_paiement where intervenant_id = 7222;


delete from mise_en_paiement where id in (
);

--update mise_en_paiement set heures = 1.38 WHERE id = 446;

update mise_en_paiement set histo_destruction = sysdate, histo_destructeur_id = 4 where id IN (
29190,
29191,
29192,
29193,
29194,
29195,
29196,
29197,
29198,
29199,
29312,
29313,
29314,
29315,
29316,
29317,
29318,
29319,
29320,
29321,
29322,
29323,
29473,
29474,
29475,
29666,
29667,
31596,
31982,
31983,
31984,
31985,
31986,
31987,
31988,
31989,
31990,
31991,
31992,
31993,
31994,
31995,
31996,
31997,
31998,
31999,
32000,
32001,
32002,
32003,
32004,
32005,
33581,
33582,
33583,
33584,
33585,
33586,
33587,
33588,
33589,
33590,
33591,
33592,
33593,
33594,
33595,
33596,
33597,
33598,
33599,
33600,
33601,
33602,
33603,
33604,
33605,
33606,
33607,
33608,
33609,
33610,
33611,
34084,
34365,
34366,
34367,
34368,
34369,
34370,
34371,
34372,
34373,
34374,
34375,
34376,
34377,
34378,
34379,
34380,
34381,
34382,
34383,
34384,
34385,
34386,
34387,
34388,
34389,
34390,
34391,
34392,
34393,
34394,
34395,
34396,
34397,
34398,
34399,
34400,
34401,
34402,
34403,
34404,
34405,
34406,
34407,
34408,
34409,
34410,
34717,
34718,
34719,
34720,
34721,
34722,
34723,
34724,
34725,
34726,
34727,
34728,
34729,
34730,
34731,
34732,
34733,
34734,
34735,
34736,
34737,
34738,
34739,
34865,
34866,
34890,
35156,
35157,
35158,
35159,
35160,
35161,
35162,
35163,
35164,
35165,
35166,
35167,
35168,
35169,
35170,
35171,
35172,
35173,
35174,
35175,
35176,
35177,
35178,
35179,
35180,
35181,
35182,
35183,
35184,
35185,
35186,
35187,
35188,
35189,
35190,
35191,
35192,
35193,
35194,
35195,
35196,
35197,
35198,
35199,
35200,
35201,
35202,
35203,
35204,
35205,
35206,
35207,
35208,
35209,
35210,
38415,
38903,
38904,
38905,
43433,
43434,
43435,
43436,
43437,
43438,
43439,
43440,
43441,
43442,
43443,
43444,
43445,
43446,
43447,
43448,
43449,
43450,
43451,
43452,
43496

);

update mise_en_paiement set periode_paiement_id = 5, date_mise_en_paiement = to_date( '29/02/2016', 'DD/MM/YYYY') WHERE
id in (

24800,
24801,
24825,
24826,
24828,
24858,
24859,
24985,
24829,
24830,
24495,
24831

);






SELECT
  *
FROM 

(
WITH sp AS (
  SELECT
    mep.formule_res_service_id,
    SUM( CASE WHEN th.code = 'fi' THEN mep.heures ELSE 0 END ) payees_fi,
    SUM( CASE WHEN th.code = 'fa' THEN mep.heures ELSE 0 END ) payees_fa,
    SUM( CASE WHEN th.code IN ('fc','fc_majorees') THEN mep.heures ELSE 0 END ) payees_fc
  FROM
    mise_en_paiement mep
    JOIN type_heures th on th.id = mep.type_heures_id
  WHERE
    1 = ose_divers.comprise_entre(mep.histo_creation,mep.histo_destruction)
  GROUP BY
    mep.formule_res_service_id
)
SELECT
  i.nom_usuel || i.prenom i_nom,
  i.source_code i_code, 
  ep.source_code elmt,
  frs.heures_compl_fi, payees_fi,
  frs.heures_compl_fa, payees_fa,
  frs.heures_compl_fc, payees_fc
FROM
  formule_resultat_service frs
  JOIN formule_resultat fr on fr.id = frs.formule_resultat_id
  JOIN sp ON sp.formule_res_service_id = frs.id
  JOIN intervenant i ON i.id = fr.intervenant_id
  JOIN service s on s.id = frs.service_id
  LEFT JOIN element_pedagogique ep ON ep.id = s.element_pedagogique_id
WHERE
  frs.heures_compl_fi < sp.payees_fi
  OR frs.heures_compl_fa < sp.payees_fa
  OR frs.heures_compl_fc < sp.payees_fc
ORDER BY
  i_nom, elmt
  
) t1
  
  
