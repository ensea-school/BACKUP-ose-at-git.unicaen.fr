-- Destruction de toute un schéma de BDD !!! ATTENTION ARCHTUNG CAUTION
SELECT 'DROP TRIGGER ' || trigger_name || ';' dsql
FROM ALL_TRIGGERS WHERE owner='DEPLOY'

UNION SELECT 'ALTER TABLE ' || table_name || ' DROP CONSTRAINT ' || constraint_name || ';' dsql
FROM ALL_CONSTRAINTS WHERE owner='DEPLOY' AND constraint_type = 'R'

UNION SELECT 'DROP SEQUENCE ' || sequence_name || ';' dsql
FROM ALL_SEQUENCES WHERE sequence_owner='DEPLOY'

UNION SELECT 'DROP VIEW ' || view_name || ';' dsql
FROM ALL_VIEWS WHERE owner = 'DEPLOY'

UNION SELECT 'DROP MATERIALIZED VIEW ' || mview_name || ';' dsql
FROM ALL_MVIEWS WHERE owner = 'DEPLOY'

UNION SELECT 'DROP INDEX ' || index_name || ';' dsql
FROM ALL_INDEXES WHERE owner = 'DEPLOY'

UNION SELECT 'DROP PACKAGE ' || object_name || ';' dsql
FROM USER_OBJECTS WHERE object_type = 'PACKAGE'

UNION SELECT 'DROP TABLE ' || table_name || ';' dsql
FROM ALL_TABLES WHERE owner='DEPLOY'

-- répétition pour ne rien oublier!!!
UNION SELECT 'DROP TABLE ' || table_name || ';' dsql
FROM ALL_TABLES WHERE owner='DEPLOY'
;