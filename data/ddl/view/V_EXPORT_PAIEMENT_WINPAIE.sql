CREATE OR REPLACE FORCE VIEW V_EXPORT_PAIEMENT_WINPAIE AS
SELECT
  annee_id,
  type_intervenant_id,
  structure_id,
  periode_id,
  intervenant_id,

  insee,
  nom,
  '20' carte,
  code_origine,
  CASE WHEN type_intervenant_code = 'P' THEN '0204' ELSE '2251' END retenue,
  '0' sens,
  'B' mc,
  nbu,
  montant,
  libelle || ' ' || LPAD(TO_CHAR(FLOOR(nbu)),2,'00') || ' H' ||
  CASE to_char(ROUND( nbu-FLOOR(nbu), 2 )*100,'00')
  WHEN ' 00' THEN '' ELSE ' ' || LPAD(ROUND( nbu-FLOOR(nbu), 2 )*100,2,'00') END libelle
FROM (
  SELECT
    i.annee_id                                                                                          annee_id,
    ti.id                                                                                               type_intervenant_id,
    ti.code                                                                                             type_intervenant_code,
    t2.structure_id                                                                                     structure_id,
    t2.periode_paiement_id                                                                              periode_id,
    i.id                                                                                                intervenant_id,
    CASE WHEN i.numero_insee IS NULL THEN '''' ELSE
      '''' || TRIM(i.numero_insee)
    END                                                                                                 insee,
    i.nom_usuel || ',' || i.prenom                                                                      nom,
    t2.code_origine                                                                                     code_origine,
    CASE WHEN ind <> CEIL(t2.nbu/max_nbu) THEN max_nbu ELSE t2.nbu - max_nbu*(ind-1) END                nbu,
    t2.nbu                                                                                              tnbu,
    OSE_FORMULE.GET_TAUX_HORAIRE_HETD( NVL(t2.date_mise_en_paiement,SYSDATE) )                          montant,
    COALESCE(t2.unite_budgetaire,'') || ' ' || to_char(i.annee_id) || ' ' || to_char(i.annee_id+1)      libelle
  FROM (
    SELECT
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      ROUND( SUM(nbu), 2) nbu,
      unite_budgetaire,
      date_mise_en_paiement
    FROM (
      WITH mep AS (
        SELECT
          -- pour les filtres
          mep.id,
          mis.structure_id,
          mep.periode_paiement_id,
          mis.intervenant_id,
          mep.heures,
          cc.unite_budgetaire,
          mep.date_mise_en_paiement,
          mis.heures_aa,
          mis.heures_ac
        FROM
          tbl_paiement  mis
          JOIN mise_en_paiement        mep ON mep.id = mis.mise_en_paiement_id AND mep.histo_destruction IS NULL
          JOIN centre_cout              cc ON cc.id = mep.centre_cout_id
          JOIN type_heures              th ON th.id = mep.type_heures_id
        WHERE
          mep.date_mise_en_paiement IS NOT NULL
          AND mep.periode_paiement_id IS NOT NULL
          AND th.eligible_extraction_paie = 1
      )
      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        2 code_origine,
        mep.heures_aa nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures_aa > 0

      UNION ALL

      SELECT
        mep.id,
        mep.structure_id,
        mep.periode_paiement_id,
        mep.intervenant_id,
        1 code_origine,
        mep.heures_ac nbu,
        mep.unite_budgetaire,
        mep.date_mise_en_paiement
      FROM
        mep
      WHERE
        mep.heures_ac > 0
    ) t1
    GROUP BY
      structure_id,
      periode_paiement_id,
      intervenant_id,
      code_origine,
      unite_budgetaire,
      date_mise_en_paiement
  ) t2
  JOIN (SELECT level ind, 99 max_nbu FROM dual CONNECT BY 1=1 AND LEVEL <= 11) tnbu ON ceil(t2.nbu / max_nbu) >= ind
  JOIN intervenant         i ON i.id = t2.intervenant_id
  JOIN statut             si ON si.id = i.statut_id
  JOIN type_intervenant   ti ON ti.id = si.type_intervenant_id
  JOIN structure           s ON s.id = t2.structure_id
) t3
ORDER BY
  annee_id, type_intervenant_id, structure_id, periode_id, nom, code_origine, nbu DESC