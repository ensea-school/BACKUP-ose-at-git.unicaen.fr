BEGIN
  DBMS_SCHEDULER.disable(name=>'"OSE"."OSE_SRC_SYNC"');
END;
/

CREATE TABLE MV_INTERVENANT_DEMO
(
  ANNEE_CREATION NUMBER 
, CIVILITE_ID NUMBER(*, 0) NOT NULL 
, NOM_USUEL VARCHAR2(120 BYTE) 
, PRENOM VARCHAR2(60 BYTE) 
, NOM_PATRONYMIQUE VARCHAR2(120 BYTE) 
, DATE_NAISSANCE DATE 
, Z_PAYS_NAISSANCE_ID VARCHAR2(9 BYTE) 
, Z_DEP_NAISSANCE_ID VARCHAR2(9 BYTE) 
, VILLE_NAISSANCE_CODE_INSEE VARCHAR2(15 BYTE) 
, VILLE_NAISSANCE_LIBELLE VARCHAR2(78 BYTE) 
, Z_PAYS_NATIONALITE_ID VARCHAR2(9 BYTE) 
, TEL_PRO VARCHAR2(33 BYTE) 
, TEL_MOBILE VARCHAR2(60 BYTE) 
, EMAIL VARCHAR2(4000 BYTE) 
, STATUT_ID NUMBER(*, 0) 
, STATUT_CODE VARCHAR2(100 CHAR) 
, Z_STRUCTURE_ID VARCHAR2(4000 CHAR) 
, SOURCE_ID NUMBER(*, 0) NOT NULL 
, SOURCE_CODE VARCHAR2(9 CHAR) 
, NUMERO_INSEE VARCHAR2(39 BYTE) 
, NUMERO_INSEE_CLE VARCHAR2(40 CHAR) 
, NUMERO_INSEE_PROVISOIRE NUMBER 
, IBAN VARCHAR2(108 BYTE) 
, BIC VARCHAR2(36 BYTE) 
, Z_GRADE_ID VARCHAR2(4000 CHAR) 
, ORDRE NUMBER 
, MIN_ORDRE NUMBER 
, Z_DISCIPLINE_ID_CNU VARCHAR2(6 BYTE) 
, Z_DISCIPLINE_ID_SOUS_CNU VARCHAR2(6 BYTE) 
, Z_DISCIPLINE_ID_SPE_CNU VARCHAR2(9 BYTE) 
, Z_DISCIPLINE_ID_DIS2DEG VARCHAR2(15 BYTE) 
, CRITERE_RECHERCHE VARCHAR2(4000 CHAR) 
, CONSTRAINT "MV_INTERVENANT_DEMO_PK" PRIMARY KEY ("SOURCE_CODE", "ORDRE")
); 
  
insert into MV_INTERVENANT_DEMO select * from mv_intervenant;
  
  
  
CREATE OR REPLACE VIEW SRC_INTERVENANT AS 
WITH srci as (
SELECT
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  COALESCE(i.date_naissance,TO_DATE('2099-01-01','YYYY-MM-DD')) date_naissance,
  pnaiss.id pays_naissance_id,
  dep.id dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  pnat.id pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  i.statut_id, i.statut_code,
  NVL(s.structure_niv2_id,s.id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  g.id grade_id,
  NVL( d.id, d99.id ) discipline_id,
  i.critere_recherche
FROM
            mv_intervenant_demo  i
       JOIN structure       s ON s.source_code = i.z_structure_id
  LEFT JOIN grade           g ON g.source_code = i.z_grade_id
  LEFT JOIN pays       pnaiss ON pnaiss.source_code = i.z_pays_naissance_id  
  LEFT JOIN pays         pnat ON pnat.source_code = i.z_pays_nationalite_id
  LEFT JOIN departement   dep ON dep.source_code = i.z_dep_naissance_id
  LEFT JOIN discipline d99 ON d99.source_code = '99'
  LEFT JOIN discipline d ON
    1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction )
    AND 1 = CASE WHEN -- si rien n'ac été défini
    
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, i.z_discipline_id_spe_cnu, i.z_discipline_id_dis2deg ) IS NULL
      AND d.source_code = '00'
    
    THEN 1 WHEN -- si une CNU ou une spécialité a été définie...
      
      COALESCE( i.z_discipline_id_cnu, i.z_discipline_id_sous_cnu, z_discipline_id_spe_cnu ) IS NOT NULL
    
    THEN CASE WHEN -- alors on teste par les sections CNU et spécialités

      (
           ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'') || ',%'
        OR ',' || d.CODES_CORRESP_2 || ',' LIKE '%,' || i.z_discipline_id_cnu || NVL(i.z_discipline_id_sous_cnu,'00') || ',%'
      )
      AND ',' || NVL(d.CODES_CORRESP_3,'000') || ',' LIKE  '%,' || NVL(CASE WHEN d.CODES_CORRESP_3 IS NOT NULL THEN z_discipline_id_spe_cnu ELSE NULL END,'000') || ',%'
    
    THEN 1 ELSE 0 END ELSE CASE WHEN -- sinon on teste par les disciplines du 2nd degré
    
      i.z_discipline_id_dis2deg IS NOT NULL
      AND ',' || NVL(d.CODES_CORRESP_4,'') || ',' LIKE  '%,' || i.z_discipline_id_dis2deg || ',%'
      
    THEN 1 ELSE 0 END END -- fin du test
WHERE
  i.ordre = i.min_ordre
)
SELECT
  null id,
  i.source_code code, i.source_code supann_emp_id,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE( 
    isai.statut_id, 
    CASE WHEN i.statut_code = 'AUTRES' AND d.statut_id IS NOT NULL THEN d.statut_id ELSE i.statut_id END
  ) statut_id,
  i. structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee
  LEFT JOIN intervenant_saisie  isai ON isai.intervenant_id = i2.id
  LEFT JOIN dossier               d  ON d.intervenant_id = i2.id

UNION ALL

SELECT
  null id,
  i.source_code code, i.source_code supann_emp_id,
  i.civilite_id,
  i.nom_usuel, i.prenom, i.nom_patronymique,
  i.date_naissance,
  i.pays_naissance_id,
  i.dep_naissance_id,
  i.ville_naissance_code_insee,  i.ville_naissance_libelle,
  i.pays_nationalite_id,
  i.tel_pro, i.tel_mobile, i.email,
  COALESCE(i2.statut_id,i.statut_id) statut_id,
  COALESCE(i2.structure_id,i.structure_id) structure_id,
  i.source_id, i.source_code,
  i.numero_insee, i.numero_insee_cle, i.numero_insee_provisoire,
  i.iban, i.bic,
  i.grade_id,
  i.discipline_id,
  unicaen_import.get_current_annee - 1 annee_id,
  i.critere_recherche
FROM
  srci i
  LEFT JOIN intervenant           i2 ON i2.source_code = i.source_code AND i2.annee_id = unicaen_import.get_current_annee - 1
  LEFT JOIN dossier               d  ON d.intervenant_id = i2.id AND 1 = ose_divers.comprise_entre( d.histo_creation, d.histo_destruction );

  
  
  




/
begin
ose_event.set_actif(false);
end;
/

UPDATE intervenant SET
  --nom_usuel                   = ,
  --prenom                      = ,
  --nom_patronymique            = ,

  date_naissance              = TO_DATE('2000-01-01', 'yyyy-mm-dd'),
  pays_naissance_id           = (SELECT id FROM pays WHERE libelle_court = 'FRANCE'),
  dep_naissance_id            = (SELECT id FROM departement WHERE source_code = '014'),
  ville_naissance_code_insee  = '14118',
  ville_naissance_libelle     = 'CAEN',
  pays_nationalite_id         = (SELECT id FROM pays WHERE libelle_court = 'FRANCE'),
  tel_pro                     = NULL,
  tel_mobile                  = NULL,
  email                       = 'prenom.nom@unicaen.fr',
  numero_insee                = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Monsieur') THEN '1000114789156' ELSE '2000114789156' END,
  numero_insee_cle            = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Monsieur') THEN '12'            ELSE '59'            END,
  numero_insee_provisoire     = 0,
  iban                        = 'FR7630006000011234567890189',
  bic                         = 'AGRIFRPPXXX'
;

-- penser à anonymiser d'abord les noms-prénoms (cf. script UnicaenCode correspondant) ! ! !


SELECT
'UPDATE dossier SET
  nom_usuel        = q''[' || i.nom_usuel || ']'',
  nom_patronymique = q''[' || i.nom_patronymique || ']'',
  prenom           = q''[' || i.prenom || ']'',
  date_naissance   = TO_DATE(''2000-01-01'', ''yyyy-mm-dd''),
  dept_naissance_id= ' || dep.id || ',
  email_perso      = null,
  pays_naissance_id= ' || pays.id || ',
  numero_insee     = ' || i.numero_insee || i.numero_insee_cle || ',
  numero_insee_est_provisoire = 0,
  adresse          = ''1, ESPLANADE DE LA PAIX, 14000, CAEN'',
  email            = ''prenom.nom@unicaen.fr'',
  telephone        = null,
  rib              = ''AGRIFRPPXXX-FR7630006000011234567890189''
WHERE intervenant_id = ' || i.id || ';'
|| '
' usql
FROM
  intervenant i
  JOIN dossier d ON d.intervenant_id = i.id
  JOIN departement dep ON dep.source_code = '014'
  JOIN pays ON pays.libelle_court = 'FRANCE';


update adresse_intervenant set 
  tel_domicile = null, 
  mention_complementaire=null, 
  batiment=null, 
  no_voie='1', 
  nom_voie='ESPLANADE DE LA PAIX', 
  localite=null, 
  code_postal='14000', 
  ville='CAEN', 
  pays_code_insee=100, 
  pays_libelle='FRANCE'
;


update fichier set 
  contenu = (select contenu from fichier where id=1), 
  type='application/pdf', 
  taille=16683, 
  description=null,
  nom='fichier_' || id || '.pdf';

select * from fichier where id = 1;


/
begin
ose_event.set_actif(true);
end;
/


select * from mv_intervenant_demo;

UPDATE mv_intervenant_demo SET
  --nom_usuel                   = ,
  --prenom                      = ,
  --nom_patronymique            = ,
  
  date_naissance              = TO_DATE('2000-01-01', 'yyyy-mm-dd'),
  z_pays_naissance_id         = '100',
  z_dep_naissance_id          = '014',
  ville_naissance_code_insee  = '14118',
  ville_naissance_libelle     = 'CAEN',
  z_pays_nationalite_id       = '100',
  tel_pro                     = NULL,
  tel_mobile                  = NULL,
  email                       = 'prenom.nom@unicaen.fr',
  numero_insee                = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Monsieur') THEN '1000114789156' ELSE '2000114789156' END,
  numero_insee_cle            = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Madame'  ) THEN '12'            ELSE '59'            END,
  numero_insee_provisoire     = 0,
  iban                        = 'FR7630006000011234567890189',
  bic                         = 'AGRIFRPPXXX',
  critere_recherche           = ose_divers.str_reduce( nom_usuel || ' ' || nom_patronymique || ' ' || prenom )
;

/
BEGIN
  DBMS_MVIEW.REFRESH('MV_INTERVENANT_RECHERCHE', 'C');
END;
/




WITH ll AS (
select l, rownum rn from (
      select 'Acheteur' L FROM DUAL
union select 'Acheteur Informatique Et Télécom' L FROM DUAL
union select 'Actuaire' L FROM DUAL
union select 'Adjoint administratif' L FROM DUAL
union select 'Adjoint administratif territorial' L FROM DUAL
union select 'Adjoint technique de recherche et de formation' L FROM DUAL
union select 'Administrateur base de données' L FROM DUAL
union select 'Administrateur De Bases De Données' L FROM DUAL
union select 'Administrateur de biens' L FROM DUAL
union select 'Administrateur De Réseau' L FROM DUAL
union select 'Administrateur judiciaire' L FROM DUAL
union select 'Affréteur' L FROM DUAL
union select 'Agent administratif et agent des services techniques' L FROM DUAL
union select 'Agent de maintenance en mécanique' L FROM DUAL
union select 'Agent de maîtrise' L FROM DUAL
union select 'Agent de police municipale' L FROM DUAL
union select 'Agent de réservation' L FROM DUAL
union select 'Agent des services techniques de préfecture' L FROM DUAL
union select 'Agent immobilier' L FROM DUAL
union select 'Agent spécialisé de police technique et scientifique' L FROM DUAL
union select 'Agent technique de recherche et de formation' L FROM DUAL
union select 'Aide comptable' L FROM DUAL
union select 'Aide de laboratoire' L FROM DUAL
union select 'Aide médico-psychologique' L FROM DUAL
union select 'Aide soignant' L FROM DUAL
union select 'Aide soignant' L FROM DUAL
union select 'Aide technique de laboratoire' L FROM DUAL
union select 'Ambulancier' L FROM DUAL
union select 'Analyste financier' L FROM DUAL
union select 'Analyste programmeur' L FROM DUAL
union select 'Animateur' L FROM DUAL
union select 'Animateur' L FROM DUAL
union select 'Animateur de club de vacances' L FROM DUAL
union select 'Animateur de formation' L FROM DUAL
union select 'animateur environnement' L FROM DUAL
union select 'Animateur socioculturel' L FROM DUAL
union select 'Antiquaire' L FROM DUAL
union select 'Archéologue' L FROM DUAL
union select 'Architecte' L FROM DUAL
union select 'Architecte De Bases De Données' L FROM DUAL
union select 'Architecte De Réseau' L FROM DUAL
union select 'Architecte Matériel' L FROM DUAL
union select 'Archiviste' L FROM DUAL
union select 'Artiste-peintre' L FROM DUAL
union select 'Assistant de conservation' L FROM DUAL
union select 'Assistant de justice' L FROM DUAL
union select 'Assistant de ressources humaines' L FROM DUAL
union select 'Assistant de service social' L FROM DUAL
union select 'Assistant de service social' L FROM DUAL
union select 'Assistant des bibliothèques' L FROM DUAL
union select 'Assistant ingénieur' L FROM DUAL
union select 'Assistant médico-technique' L FROM DUAL
union select 'Assistant socio-éducatif' L FROM DUAL
union select 'Assistant son' L FROM DUAL
union select 'Assistant vétérinaire' L FROM DUAL
union select 'Assistante de gestion PMI/PME' L FROM DUAL
union select 'Assistante maternelle' L FROM DUAL
union select 'Assistante sociale' L FROM DUAL
union select 'Astronome' L FROM DUAL
union select 'Attaché de conservateur de patrimoine' L FROM DUAL
union select 'Attaché de police' L FROM DUAL
union select 'Attaché de préfecture' L FROM DUAL
union select 'Attaché de presse' L FROM DUAL
union select 'Auditeur Informatique' L FROM DUAL
union select 'Auteur-scénariste multimédia' L FROM DUAL
union select 'Auxiliaire de puériculture' L FROM DUAL
union select 'Auxiliaire de vie sociale' L FROM DUAL
union select 'Auxilliaire de vie' L FROM DUAL
union select 'Avocat' L FROM DUAL
union select 'Barman' L FROM DUAL
union select 'Bibliothécaire' L FROM DUAL
union select 'Bibliothécaire adjoint spécialisé' L FROM DUAL
union select 'Bijoutier joaillier' L FROM DUAL
union select 'Billettiste' L FROM DUAL
union select 'Bio-informaticien' L FROM DUAL
union select 'Biologiste, Vétérinaire, Pharmacien' L FROM DUAL
union select 'Bobinier de la construction électrique' L FROM DUAL
union select 'Boucher' L FROM DUAL
union select 'Boulanger' L FROM DUAL
union select 'Brasseur malteur' L FROM DUAL
union select 'Bronzier' L FROM DUAL
union select 'Bûcheron' L FROM DUAL
union select 'Cadreur' L FROM DUAL
union select 'Capitaine de Sapeur-Pompier' L FROM DUAL
union select 'Carreleur' L FROM DUAL
union select 'Carrossier réparateur' L FROM DUAL
union select 'Caviste' L FROM DUAL
union select 'Charcutier-traiteur' L FROM DUAL
union select 'Chargé de clientèle' L FROM DUAL
union select 'Chargé De Référencement' L FROM DUAL
union select 'Chargé de relations publiques' L FROM DUAL
union select 'Charpentier' L FROM DUAL
union select 'Chaudronnier' L FROM DUAL
union select 'Chef de chantier' L FROM DUAL
union select 'Chef de comptoir' L FROM DUAL
union select 'Chef de fabrication' L FROM DUAL
union select 'Chef de produits voyages' L FROM DUAL
union select 'Chef De Projet - Project Manager' L FROM DUAL
union select 'Chef de projet informatique' L FROM DUAL
union select 'Chef de projet multimedia' L FROM DUAL
union select 'Chef de publicité' L FROM DUAL
union select 'Chef de rayon' L FROM DUAL
union select 'Chef de service de Police municipale' L FROM DUAL
union select 'Chef opérateur' L FROM DUAL
union select 'Chercheur' L FROM DUAL
union select 'Chercheur En Informatique' L FROM DUAL
union select 'Chirurgien-dentiste' L FROM DUAL
union select 'Chocolatier confiseur' L FROM DUAL
union select 'Clerc de notaire' L FROM DUAL
union select 'Coiffeur' L FROM DUAL
union select 'Comédien' L FROM DUAL
union select 'Commis de cuisine' L FROM DUAL
union select 'Commissaire de police' L FROM DUAL
union select 'Commissaire priseur' L FROM DUAL
union select 'comportementaliste' L FROM DUAL
union select 'Comptable' L FROM DUAL
union select 'Concepteur De Jeux Électroniques' L FROM DUAL
union select 'Concepteur rédacteur' L FROM DUAL
union select 'Conducteur' L FROM DUAL
union select 'Conducteur de machine à imprimer simple' L FROM DUAL
union select 'Conducteur de machines' L FROM DUAL
union select 'Conducteur de machines agro' L FROM DUAL
union select 'Conducteur de taxi' L FROM DUAL
union select 'conducteur de train' L FROM DUAL
union select 'Conducteur de travaux' L FROM DUAL
union select 'Conducteur routier' L FROM DUAL
union select 'Conseiller en développement touristique' L FROM DUAL
union select 'Conseiller en économie sociale et familiale' L FROM DUAL
union select 'Conseiller socio-éducatif' L FROM DUAL
union select 'Conseiller territorial des activités physiques et sportives' L FROM DUAL
union select 'Conservateur de bibliothèque' L FROM DUAL
union select 'Conservateur du patrimoine' L FROM DUAL
union select 'Consultant E-Business' L FROM DUAL
union select 'Consultant En Conduite Du Changement' L FROM DUAL
union select 'Consultant En E-Learning' L FROM DUAL
union select 'Consultant En Gestion De La Relation Client' L FROM DUAL
union select 'Consultant En Technologies' L FROM DUAL
union select 'Consultant Erp' L FROM DUAL
union select 'Consultant Fonctionnel' L FROM DUAL
union select 'Consultant Informatique' L FROM DUAL
union select 'Contrôleur aérien' L FROM DUAL
union select 'Contrôleur de gestion' L FROM DUAL
union select 'Contrôleur de travaux' L FROM DUAL
union select 'Contrôleur des services techniques du matériel' L FROM DUAL
union select 'Contrôleur du travail' L FROM DUAL
union select 'Contrôleur en électricité et électronique' L FROM DUAL
union select 'Convoyeur de fonds' L FROM DUAL
union select 'Coordinatrice de crèches' L FROM DUAL
union select 'Correcteur' L FROM DUAL
union select 'Costumier-habilleur' L FROM DUAL
union select 'Couvreur' L FROM DUAL
union select 'Créateur de parfum' L FROM DUAL
union select 'Cuisinier' L FROM DUAL
union select 'Cyberdocumentaliste' L FROM DUAL
union select 'Danseur' L FROM DUAL
union select 'Décorateur-scénographe' L FROM DUAL
union select 'Délégué médical' L FROM DUAL
union select 'Déménageur' L FROM DUAL
union select 'Démographe' L FROM DUAL
union select 'Dépanneur tv électroménager' L FROM DUAL
union select 'Designer automobile' L FROM DUAL
union select 'Dessinateur de presse' L FROM DUAL
union select 'Dessinateur industriel' L FROM DUAL
union select 'Détective privé' L FROM DUAL
union select 'Développeur' L FROM DUAL
union select 'Diététicien' L FROM DUAL
union select 'Directeur artistique' L FROM DUAL
union select 'Directeur Commercial' L FROM DUAL
union select 'Directeur de collection' L FROM DUAL
union select 'Directeur de parc naturel' L FROM DUAL
union select 'Directeur De Projet' L FROM DUAL
union select 'Directeur de ressources humaines' L FROM DUAL
union select 'Directeur des soins' L FROM DUAL
union select 'Directeur Technique' L FROM DUAL
union select 'Docker' L FROM DUAL
union select 'Documentaliste' L FROM DUAL
union select 'Douanier' L FROM DUAL )

)
select
  'update fonction_referentiel SET libelle_court = q''[' || ll.l || ']'', libelle_long = q''[' || ll.l || ']'' WHERE id = ' || fr.id || ';' usql
from
  fonction_referentiel fr
  JOIN ll ON ll.rn = fr.id
ORDER BY rownum;

DELETE FROM indic_modif_dossier;


SELECT
  i.annee_id, i.nom_usuel, i.prenom, i.code, si.source_code
FROM
  v_diff_intervenant i
  JOIN statut_intervenant si ON si.id = i.statut_id
WHERE
  import_action = 'insert'
  AND si.source_code = 'AUTRES'
  AND rownum < 50
  AND i.annee_id = 2016
  
