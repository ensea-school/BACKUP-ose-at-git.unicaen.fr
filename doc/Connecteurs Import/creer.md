# Créer ses propres connecteurs

## Introduction

Le mécanisme d'import de données se fait au niveau de la base de données, dans des tables prévues à cet effet.

Cette page vous founira, table par table, les explications utiles pour pouvoir créer vos propres connecteurs.

En face de chaque table synchronisable de OSE que vous souhaitez alimenter, il vous faudra créer une vue source
dont le nom sera SRC_ + nom de la table. Par exemple SRC_PAYS pour peupler la table PAYS.

Pour chaque table, des indications spécifiques vous seront indiquées ici.


Les tables sont réparties par grand domaine (RH, Offre de formation, Paye, Services).

Il est important de travailler en respectant l'ordre ci-dessous, car il y a des démendances entre tables.



## Méthodologie

Vous devrez d'abord créer vos sources de données dans OSE.
Voici comment ajouter une nouvelle source de données : 

```sql
BEGIN
  unicaen_import.add_source('code_connecteur', 'Libellé de votre connecteur');
  commit;
END;
```

Ensuite, pour chaque table voulue, il est recommandé de procéder en plusieurs étapes :

Récupération via une requête spécifique de toutes les données nécessaires issues de votre logiciel source. Si votre logiciel source n'(est pas accéssible via un DbLink, 
alors il vous faudra stocker ces données dans une table intermédiaire que vous peuplerez par vos propres moyens, 
à l'aide d'une moulinette qui exploite les services web de votre application par exemple).

Prenons l'exemple des grades. La table GRADE doit être synchronisée à partir d'une vue source SRC_GRADE qu'il faudra mettre en place.
La première étape est de se rendre dans la liste des tables ci-dessous, afin de prendre connaissance de la liste des données nécessaires.
La voici pour [GRADE](Création tables/GRADE.md).

Créez votre propre requête. En prenant appui sur d'Harpège pour l'exemple, nous avons le résultat suivant :
```sql
SELECT
  g.ll_grade  libelle_long,
  g.lc_grade  libelle_court,
  'Harpege'   z_source_id,
  g.c_grade   source_code,
  g.echelle   echelle,
  g.c_corps   z_corps_id
FROM
  grade@harpprod g
WHERE
  SYSDATE BETWEEN COALESCE(g.d_ouverture,SYSDATE) AND COALESCE(g.d_fermeture+1,SYSDATE)
```


Une fois que la requête fonctionne, créez la vue source SRC_*table-destination* qui exploitera votre requête et fera le lien avec OSE en injectant les identifiants dons OSE a besoin au moyen de jointures.
Toujours pour les grades, voici la vue source finale :
```sql
CREATE OR REPLACE FORCE VIEW SRC_GRADE AS
WITH harpege_query AS (

  -- Vous retrouvez la requête ici
  SELECT
    g.ll_grade  libelle_long,
    g.lc_grade  libelle_court,
    'Harpege'   z_source_id,
    g.c_grade   source_code,
    g.echelle   echelle,
    g.c_corps   z_corps_id
  FROM
    grade@harpprod g
  WHERE
    SYSDATE BETWEEN COALESCE(g.d_ouverture,SYSDATE) AND COALESCE(g.d_fermeture+1,SYSDATE)


)
SELECT
  hq.libelle_long   libelle_long,
  hq.libelle_court  libelle_court,
  s.id              source_id,
  hq.source_code    source_code,
  hq.echelle        echelle,
  c.id              corps_id
FROM
       harpege_query hq
  JOIN source         s ON s.code        = hq.z_source_id
  JOIN corps          c ON c.source_code = hq.z_corps_id;
```

Les données sont maintenant encapsulées et prêtes à être syncghronisées dans OSE.

Pour les tables au contenu plus volumineux ou bien si vous n'avez pas la possiblité de faire un DbLink, 
vous pouvez appuyer votre vue source sur une vue matérialisée à l'instar de ce qui est préconisé pour les intervenants.
Vous pouvez aussi créer une table de votre cru, peuplée par un script externe, puis y faire appel dans la vue source :

```sql
CREATE OR REPLACE FORCE VIEW SRC_GRADE AS
SELECT
  hq.libelle_long   libelle_long,
  hq.libelle_court  libelle_court,
  s.id              source_id,
  hq.source_code    source_code,
  hq.echelle        echelle,
  c.id              corps_id
FROM
       votre-vue-materialisée-ou-votre-table hq
  JOIN source         s ON s.code        = hq.z_source_id
  JOIN corps          c ON c.source_code = hq.z_corps_id;
``` 



3. Enfin, vous devrez [activer, puis synchroniser le tout](activer-synchroniser.md), table par table.

Exemple de vue source avec imbrication :
La vue [SRC_GRADE](Harpège/SRC_GRADE.sql) du connecteur Harpège, où la sous-requête `harpege_query` récupère les données,
que la vue source exploite ensuite en y injectant les identifiants OSE à l'aide de jointures.


Par convention, un champ qui contient une donnée qui sera ensuite remplacée par un identifiant OSE commence par `Z_`.
Par exemple, dans la vue [SRC_GRADE](Harpège/SRC_GRADE.sql) du connecteur Harpège, Z_CORPS_ID remonte le code du corps auquel le grade appartient.
Ensuite, la vue source fait une jointure vers la table des corps `JOIN corps          c ON c.source_code = hq.z_corps_id`
et elle retourne `c.id CORPS_ID` qui contient donc l'ID OSE du corps. 

Séparer ainsi la récupération des données et la récupération des identifiants apporte d'avantage de lisibilité :
les parties "récupération" et "liaison" sont bien distinctes.


## Liste des tables

### Nomenclatures diverses

| Table | Descriptif |
| ----- | ---------- |
| [PAYS](Création tables/PAYS.md)                   | Liste des pays |
| [DEPARTEMENT](Création tables/DEPARTEMENT.md)     | Liste des départements |
| [VOIRIE](Création tables/VOIRIE.md)               | Liste des voiries (rue, allée, boulevard, etc.) |
| [ETABLISSEMENT](Création tables/ETABLISSEMENT.md) | Liste des établissements |
| [STRUCTURE](Création tables/STRUCTURE.md)         | Liste des structures (composantes, etc.) |
| [DISCIPLINE](Création tables/DISCIPLINE.md)       | Liste des disciplines (équivalent personnalisé des sections CNU, des sections du second degré, etc.) |

### Données "RH"

| Table | Descriptif |
| ----- | ---------- |
| [AFFECTATION](Création tables/AFFECTATION.md)                     | Liste des affectations (pour donner des rôles aux utilisateurs) |
| [EMPLOYEUR](Création tables/EMPLOYEUR.md)                         | Liste des employeurs |
| [CORPS](Création tables/CORPS.md)                                 | Liste des corps |
| [GRADE](Création tables/GRADE.md)                                 | Liste des grades |
| [INTERVENANT](Création tables/INTERVENANT.md)                     | Liste des intervenants |
| [AFFECTATION_RECHERCHE](Création tables/AFFECTATION_RECHERCHE.md) | Liste des affectations de recherche |

### Données comptables

| Table | Descriptif |
| ----- | ---------- |
| [DOMAINE_FONCTIONNEL](Création tables/DOMAINE_FONCTIONNEL.md)     | Liste des domaines fonctionnels |
| [CENTRE_COUT](Création tables/CENTRE_COUT.md)                     | Liste des centres de coûts |
| [CENTRE_COUT_EP](Création tables/CENTRE_COUT_EP.md)               | Liste des centres de coûts liés aux éléments pédagogiques |
| [CENTRE_COUT_STRUCTURE](Création tables/CENTRE_COUT_STRUCTURE.md) | Liste des centres de coûts liés aux structures |

### Données décrivant l'offre de formation

| Table | Descriptif |
| ----- | ---------- |
| [GROUPE_TYPE_FORMATION](Création tables/GROUPE_TYPE_FORMATION.md) | Liste des groupes de type de formation |
| [TYPE_FORMATION](Création tables/TYPE_FORMATION.md)               | Liste des types de formation |
| [ETAPE](Création tables/ETAPE.md)                                 | Liste des étapes (années de formation, L1 de Droit par exemple) |
| [ELEMENT_PEDAGOGIQUE](Création tables/ELEMENT_PEDAGOGIQUE.md)     | Liste des éléments pédagogiques |
| [CHEMIN_PEDAGOGIQUE](Création tables/CHEMIN_PEDAGOGIQUE.md)       | Liste des chemins pédagogiques |
| [VOLUME_HORAIRE_ENS](Création tables/VOLUME_HORAIRE_ENS.md)       | Liste des volumes horaires d'enseignement (charges) |
| [EFFECTIFS](Création tables/EFFECTIFS.md)                         | Liste des effectifs |
| [EFFECTIFS_ETAPE](Création tables/EFFECTIFS_ETAPE.md)             | Liste des effectifs par étapes |
| [ELEMENT_TAUX_REGIMES](Création tables/ELEMENT_TAUX_REGIMES.md)   | Liste des éléments de taux de régime |
| [NOEUD](Création tables/NOEUD.md)                                 | Liste des noeuds |
| [LIEN](Création tables/LIEN.md)                                   | Liste des liens |
| [SCENARIO_NOEUD](Création tables/SCENARIO_NOEUD.md)               | Liste des paramétrages de noeuds par scénarios |
| [SCENARIO_LIEN](Création tables/SCENARIO_LIEN.md)                 | Liste des paramétrages de liens par scénarios |

Si vous ne souhaitez pas exploiter le module charges de OSE, seules les tables [GROUPE_TYPE_FORMATION](Création tables/GROUPE_TYPE_FORMATION.md),
[TYPE_FORMATION](Création tables/TYPE_FORMATION.md), [ETAPE](Création tables/ETAPE.md),
[ELEMENT_PEDAGOGIQUE](Création tables/ELEMENT_PEDAGOGIQUE.md), [CHEMIN_PEDAGOGIQUE](Création tables/CHEMIN_PEDAGOGIQUE.md)
et [VOLUME_HORAIRE_ENS](Création tables/VOLUME_HORAIRE_ENS.md) pourront être peuplées. 

Les autres tables ne servent que pour le module charges.

### Données liées aux services d'enseignement

| Table | Descriptif |
| ----- | ---------- |
| [SERVICE](Création tables/SERVICE.md)                           | Liste des services (éléments pédagogiques pour les intervenants) |
| [SERVICE_REFERENTIEL](Création tables/SERVICE_REFERENTIEL.md)   | Liste des servcies référentiels (fonctions référentiels pour les intervenants) |
| [VOLUME_HORAIRE](Création tables/VOLUME_HORAIRE.md)             | Liste des volumes horaires (nb d'heures de CM, TD, TP par ligne de service) |
| [VOLUME_HORAIRE_REF](Création tables/VOLUME_HORAIRE_REF.md)     | Liste des volume horaires de référentiel (nb d'heures par service référentiel) |
| [TYPE_INTERVENTION_EP](Création tables/TYPE_INTERVENTION_EP.md) | Liste des types d'intervention (CM, TD, TP, ...) par élément pédagogique |
| [TYPE_MODULATEUR_EP](Création tables/TYPE_MODULATEUR_EP.md)     | Liste des types de modulateurs par éléments pédagogiques |

Les tables [SERVICE](Création tables/SERVICE.md), [SERVICE_REFERENTIEL](Création tables/SERVICE_REFERENTIEL.md),
[VOLUME_HORAIRE](Création tables/VOLUME_HORAIRE.md) et [VOLUME_HORAIRE_REF](Création tables/VOLUME_HORAIRE_REF.md)
pourront acceuillir des données issues des agendas pour injecter les services d'enseignement dans OSE directement.

### Autres

| Table | Descriptif |
| ----- | ---------- |
| [VOLUME_HORAIRE_CHARGE](Création tables/VOLUME_HORAIRE_CHARGE.md) | Table non exploitée : à ignorer |

