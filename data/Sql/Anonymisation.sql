/
alter trigger "OSE"."F_INTERVENANT" disable;
alter trigger "OSE"."PJ_TRG_DOSSIER" disable;
alter trigger "OSE"."PJ_TRG_DOSSIER_S" disable;
alter trigger "OSE"."WF_TRG_DOSSIER" disable;
alter trigger "OSE"."WF_TRG_DOSSIER_S" disable;

/

UPDATE intervenant SET
  --nom_usuel                   = ,
  --prenom                      = ,
  --nom_patronymique            = ,
  
  date_naissance              = TO_DATE('2000-01-01', 'yyyy-mm-dd'),
  pays_naissance_code_insee   = '100',
  pays_naissance_libelle      = 'FRANCE',
  dep_naissance_code_insee    = '014',
  dep_naissance_libelle       = 'CALVADOS',
  ville_naissance_code_insee  = '14118',
  ville_naissance_libelle     = 'CAEN',
  pays_nationalite_code_insee = '100',
  pays_nationalite_libelle    = 'FRANCE',
  tel_pro                     = NULL,
  tel_mobile                  = NULL,
  email                       = 'prenom.nom@unicaen.fr',
  numero_insee                = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Monsieur') THEN '1000114789156' ELSE '2000114789156' END,
  numero_insee_cle            = CASE WHEN civilite_id = (SELECT id FROM civilite WHERE libelle_long = 'Madame'  ) THEN '12'            ELSE '59'            END,
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
WHERE intervenant_id = ' || i.id || ';
' usql
FROM
  intervenant i
  JOIN dossier d ON d.intervenant_id = i.id
  JOIN departement dep ON dep.source_code = '14'
  JOIN pays ON pays.libelle_court = 'FRANCE'
;


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


select * from intervenant where source_code = '25481';
select * from dossier where intervenant_id = 5768;
select * from piece_jointe where dossier_id = 3895;
select * from PIECE_JOINTE_FICHIER where piece_jointe_id = 8869;
select * from fichier where id = 14892;
select * from type_piece_jointe;
update fichier set contenu = (select contenu from fichier where id=14892);




/
alter trigger "OSE"."F_INTERVENANT" enable;
alter trigger "OSE"."PJ_TRG_DOSSIER" enable;
alter trigger "OSE"."PJ_TRG_DOSSIER_S" enable;
alter trigger "OSE"."WF_TRG_DOSSIER" enable;
alter trigger "OSE"."WF_TRG_DOSSIER_S" enable;
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