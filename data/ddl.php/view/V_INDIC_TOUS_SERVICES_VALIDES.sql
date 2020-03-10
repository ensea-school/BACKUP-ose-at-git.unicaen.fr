CREATE OR REPLACE FORCE VIEW V_INDIC_TOUS_SERVICES_VALIDES AS
with struct_ens_non_valides as (
    -- intervenants et structures d'intervention pour lesquelles des VH d'enseignement NE SONT PAS validés
    SELECT distinct s.intervenant_id, ep.structure_id, vh.type_volume_horaire_id
    FROM SERVICE s
    INNER JOIN ELEMENT_PEDAGOGIQUE ep    ON s.ELEMENT_PEDAGOGIQUE_ID = ep.ID   AND ep.HISTO_DESTRUCTION IS NULL
    INNER JOIN VOLUME_HORAIRE vh         ON s.ID = vh.SERVICE_ID               AND vh.HISTO_DESTRUCTION IS NULL
    LEFT JOIN VALIDATION_VOL_HORAIRE vvh ON vvh.volume_horaire_id = vh.id
    LEFT JOIN VALIDATION val             ON val.ID = vvh.validation_id         AND val.HISTO_DESTRUCTION IS NULL
    WHERE s.HISTO_DESTRUCTION IS NULL
    AND val.id is null
),
struct_ref_non_valides as (
    -- intervenants et structures d'intervention pour lesquelles des VH de référentiel NE SONT PAS validés
    SELECT distinct s.intervenant_id, s.structure_id, vh.type_volume_horaire_id
    FROM SERVICE_REFERENTIEL s
    INNER JOIN FONCTION_REFERENTIEL f        ON s.FONCTION_ID = f.ID               AND f.HISTO_DESTRUCTION IS NULL
    INNER JOIN VOLUME_HORAIRE_REF vh         ON s.ID = VH.SERVICE_REFERENTIEL_ID   AND vh.HISTO_DESTRUCTION IS NULL
    LEFT JOIN VALIDATION_VOL_HORAIRE_REF vvh ON VVH.VOLUME_HORAIRE_REF_ID = vh.id
    LEFT JOIN VALIDATION val                 ON val.ID = vvh.validation_id         AND val.HISTO_DESTRUCTION IS NULL
    WHERE s.HISTO_DESTRUCTION IS NULL
    AND val.id is null
)
-- intervenants et structures d'intervention pour lesquelles tous les VH de référentiel et tous les VH d'enseignement SONT validés
SELECT distinct s.intervenant_id, ep.structure_id, vh.type_volume_horaire_id
FROM SERVICE s
INNER JOIN ELEMENT_PEDAGOGIQUE ep     ON s.ELEMENT_PEDAGOGIQUE_ID = ep.ID   AND ep.HISTO_DESTRUCTION IS NULL
INNER JOIN VOLUME_HORAIRE vh          ON s.ID = vh.SERVICE_ID               AND vh.HISTO_DESTRUCTION IS NULL
WHERE not exists (
    SELECT * from struct_ens_non_valides ens_nv
    where ens_nv.INTERVENANT_ID = s.intervenant_id and ens_nv.structure_id = ep.structure_id and ens_nv.type_volume_horaire_id = vh.type_volume_horaire_id
)
-----
UNION
-----
SELECT distinct s.intervenant_id, s.structure_id, vh.type_volume_horaire_id
FROM SERVICE_REFERENTIEL s
INNER JOIN FONCTION_REFERENTIEL f     ON s.FONCTION_ID = f.ID               AND f.HISTO_DESTRUCTION IS NULL
INNER JOIN VOLUME_HORAIRE_REF vh      ON s.ID = VH.SERVICE_REFERENTIEL_ID   AND vh.HISTO_DESTRUCTION IS NULL
WHERE s.HISTO_DESTRUCTION IS NULL
and not exists (
    SELECT * from struct_ref_non_valides ref_nv
    where ref_nv.INTERVENANT_ID = s.intervenant_id and ref_nv.structure_id = s.structure_id and ref_nv.type_volume_horaire_id = vh.type_volume_horaire_id
)