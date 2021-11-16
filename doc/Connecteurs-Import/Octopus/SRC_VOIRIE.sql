CREATE OR REPLACE VIEW SRC_VOIRIE AS
SELECT
    vvsn.code         code,
    vvsn.libelle      libelle,
    src.id            source_id,
    vvsn.code         source_code,
    vvsn.code         core
FROM octo.v_via_siham_nomenclatures@octoprod vvsn
JOIN source src ON src.code = 'Octopus'
WHERE vvsn.cdstco = 'VNT'



