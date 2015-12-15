WITH 
heures_validees as (
  SELECT
    s.intervenant_id intervenant_id,
    s.structure_ens_id structure_id,
    s.annee_id annee_id,
    SUM(vh.heures) heures_validees
  FROM
    service s
    JOIN volume_horaire vh ON vh.service_id = s.id AND vh.histo_destruction IS NULL
    JOIN type_volume_horaire tvh ON tvh.id = vh.type_volume_horaire_id AND tvh.histo_destruction IS NULL
  WHERE
    s.histo_destruction IS NULL
    AND EXISTS( SELECT * FROM VALIDATION_VOL_HORAIRE vvh JOIN validation v ON v.id = vvh.validation_id AND v.histo_destruction IS NULL WHERE vvh.volume_horaire_id = vh.id )
    AND (tvh.code IS NULL OR tvh.code = 'PREVU')
  GROUP BY
    s.intervenant_id, s.structure_ens_id, s.annee_id 
),
agrements as (
  SELECT
    a.intervenant_id intervenant_id,
    a.structure_id structure_id,
    a.annee_id annee_id
  FROM
    agrement a
  WHERE
    a.histo_destruction IS NULL
),
contrats as (
  SELECT
    c.intervenant_id,
    c.structure_id,
    CASE WHEN v.id IS NULL THEN 0 ELSE 1 END valide
  FROM
    contrat c
    LEFT JOIN validation v ON v.id = c.validation_id AND v.histo_destruction IS NULL
  WHERE
    c.histo_destruction IS NULL
),
contrats_harpege AS (
SELECT DISTINCT aff.no_dossier_pers intervenant_source_code, ct.c_type_contrat_trav ct_code
    FROM
        affectation@harpprod aff
        LEFT JOIN contrat_travail@harpprod ct ON ( aff.no_dossier_pers = ct.no_dossier_pers AND aff.no_contrat_travail = ct.no_contrat_travail )
        LEFT JOIN type_contrat_travail@harpprod tct ON ( ct.c_type_contrat_trav = tct.c_type_contrat_trav )
    WHERE
      aff.d_deb_affectation <= SYSDATE
      AND (  aff.d_fin_affectation IS NULL OR aff.d_fin_affectation >= SYSDATE )
)
SELECT
  ch.ct_code code_contrat_travail,
  i.source_code code_harpege,
  i.nom_usuel,
  i.prenom,
  s.libelle_court structure,
  NVL( hv.heures_validees, 0 ) heures_validees,
  CASE WHEN a.intervenant_id IS NULL THEN 'Non' ELSE 'Oui' END agrement,
  CASE WHEN C.VALIDE IS NULL THEN 'Non' WHEN C.VALIDE = 1 THEN 'Validé' ELSE 'Edité' END  contrat
FROM
    intervenant i
    JOIN      statut_intervenant  si ON si.id = i.statut_id
    JOIN      type_intervenant    ti ON ti.id = i.type_id
    JOIN      heures_validees     hv ON hv.intervenant_id = i.id AND hv.annee_id = 2014
    JOIN      structure           s  ON s.id = hv.structure_id
    LEFT JOIN agrements           a  ON a.intervenant_id = i.id AND a.annee_id = 2014 AND a.structure_id = s.id
    LEFT JOIN contrats            c  ON c.intervenant_id = i.id AND c.structure_id = s.id
    LEFT JOIN contrats_harpege    ch ON CH.INTERVENANT_SOURCE_CODE = i.source_code 
WHERE
  i.histo_destruction IS NULL
  AND ti.code = 'E'
ORDER BY
  code_contrat_travail, nom_usuel, prenom, structure;
  
  