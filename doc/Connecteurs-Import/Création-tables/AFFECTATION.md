# AFFECTATION

Liste des affectations (pour donner des rôles aux utilisateurs)

Colonnes nécessaires :

|Colonne         |Type    |Longueur|Nullable|Commentaire              |
|----------------|--------|--------|--------|-------------------------|
|Z_UTILISATEUR_ID|NUMBER  |        |Non     |==> UTILISATEUR.USERNAME |
|Z_ROLE_ID       |NUMBER  |        |Non     |==> ROLE.CODE            |
|Z_STRUCTURE_ID  |NUMBER  |        |Oui     |==> STRUCTURE.SOURCE_CODE|
|Z_SOURCE_ID     |NUMBER  |        |Non     |==> SOURCE.CODE          |
|SOURCE_CODE     |VARCHAR2|100     |Oui     |                         |


Il doit y avoir unicité du trouple de colonnes [Z_UTILISATEUR_ID,Z_ROLE_ID,Z_STRUCTURE_ID]

Exemple de requête :
```sql
CREATE OR REPLACE VIEW SRC_AFFECTATION AS
WITH a AS (

  -- Votre requête, à personnaliser
  SELECT
    'choisir un SOURCE.CODE' z_source_id,
    'z_utilisateur_id'       z_utilisateur_id,
    NULL                     z_structure_id,
    'z_role_id'              z_role_id,
    'source_code'            source_code
  FROM
    dual -- utiliser votre source
  -- fin de votre requête

)
SELECT
  s.id          structure_id,
  u.id          utilisateur_id,
  r.id          role_id,
  src.id        source_id,
  a.source_code source_code
FROM
                           a
       JOIN source       src ON src.code = a.z_source_id
  LEFT JOIN utilisateur    u ON u.username = a.z_utilisateur_id
  LEFT JOIN structure      s ON s.source_code = a.z_structure_id
  LEFT JOIN role           r ON r.code = a.z_role_id
```