
# Mécanisme

L'import de données se passe au niveau de la base de données.

Toutes les données exploitées par l'application doivent être enregistrées dans sa base de données.
Ceci implique donc d'y importer les données et de les synchroniser à intervalle régulier afin de les maintenir à jour.

Un certain nombre de tables de la base de données sont importables, c'est-à-dire qu'elles possèdent deux colonnes, SOURCE_ID et SOURCE_CODE qui permettent respectivement de
* Savoir quelle est la source de la donnée (SOURCE_ID faisant référence à SOURCE.ID, donc l'identifiant de la source).
* avoir, ligne par ligne, un identifiant **unique** qui permet d'établir la correspondance avec la donnée d'origine.  

Les données à importer devront être listées dans des vues écrites au format attendu par OSE.
Le format est disponible directement dans l'application, page Administration / Synchronisation / Tableau de bord principal.
Généralement, les vues accèdent aux données en passant par des DBLinks, mais rien n'empêche de faire autrement.

OSE permet de faire quatre opérations d'importation :
* INSERT : pour ajouter une donnée
* UPDATE : mise à jour d'une donnée
* DELETE : pour supprimer une donnée (sachant que dans OSE les données ne sont pas réellement supprimées, mais historisées avec un horodatage)
* UNDELETE : pour restaurer une donnée qui avait été supprimée

# Sources de données

OSE peut accepter plusieurs sources de données.

Deux d'entres elles, particulières, sont présentes par défaut dans l'application.

La source "OSE" n'est pas vraiment une source de données. Une donnée dont la source est OSE signifie qu'elle a été saisie directement dans l'application.
Cette donnée ne pourra donc pas être récupérée ailleurs et elle ne peut pas être mise à jour de manière automatique.

La source "Calcul", quand à elle, est utilisée pour des données qui n'ont pas été saisies dans l'application, mais calculées et intégrées dans l'application 
à partir d'autres données déjà présentes dans OSE en utilisant le mécanisme d'import de données.
Par exemple, dans le tableau ci-dessous nous avons la table TYPE_INTERVENTION_EP. Cette table permet de lister tous les types d'intervention (CM, TD) 
pour lesquels il est possible de saisir des heures d'enseignement pour chaque élément pédagogique.
Cette information peut être déduite d'une autre, à savoir la présence de charges d'enseignement.
Donc nous prenons les charges et s'il il y en a par exemple en CM sur un élément de Maths, alors TYPE_INTERVENTION_EP sera peuplé
avec une ligne ("CM", "Maths") ce qui aura pour conséquence de pouvoir saisir des heures de service en CM sur cet élément de maths.

Les autres sources dont vous aurez la nécessité seront créées au besoin par vos soins.

Il est possible, pour une même table, d'intégrer des données provenant de plusieurs sources.
Par exemple à Caen l'offre de formation est à la fois
* importée d'Apogée
* importée de FCA Manager
* saisie directement dans OSE

Chaque élément aura donc comme source soit Apogée, soit FCA Manager, soit OSE.

Il n'existe en revanche qu'une seule vue source par table.
Il vous revient donc de fusionner les données de ces différentes sources au moyen d'un "UNION ALL".
Par ailleurs, OSE ne gère pas le dédoublonnage des données sources. A vous, donc, de gérer cet aspect. 
**Pour chaque vue source, la colonne SOURCE_CODE doit avoir des valeurs uniques**.

# Connecteurs Import de OSE

Il existe déjà plusieurs connecteurs. Ceux-ci vous sont fournis à titre d'exemple.
Ils devront être adaptés aux spécifités de votre système d'information.

En voici la liste :

* [Harpège](Harpège/Connecteur.md) pour les données RH et diverses
* [Sifac](Sifac/Connecteur.md) pour les données comptables
* [Apogée](Sifac/Connecteur.md) pour l'offre de formation

Et voici la matrice des connecteurs qui reprend, table par table, ce qu'ils peuvent fournir :

<table>
  <tr>
    <th>Table</th>
    <th>Apogée</th>
    <th>FCA Manager</th> 
    <th>Harpège</th>
    <th>Sifac</th>
    <th>Calcul</th>
    <th>Description</th>
  </tr>
  <tr>
    <th colspan="50">Données "RH"</th>
  </tr>
  <tr>
    <td>AFFECTATION</td><td></td><td></td><td></td><td></td><td></td><td>Affectation des utilisateurs à des rôles</td>
  </tr>
  | AFFECTATION_RECHERCHE |        |             | Oui     |       |        | Affectations de recherche des intervenants |
</table>

| Table                 | Apogée | FCA Manager | Harpège | Sifac | Calcul | Description | 
| --------------------- | ------ | ----------- | ------- | ----- | ------ | ----------- |
| _Données "RH"_ |
| AFFECTATION           |        |             |         |       |        | Affectation des utilisateurs à des rôles |				 
| AFFECTATION_RECHERCHE |        |             | Oui     |       |        | Affectations de recherche des intervenants |	 
| GRADE 				|        |             | Oui     |       |        | Liste des grades |
| INTERVENANT 			|        |             | Oui     |       |        | Intervenants |
|  |
| _Nomenclatures diverses_ |
| CORPS 				|        |             | Oui     |       |        | Liste des corps |
| DEPARTEMENT 			|        |             | Oui     |       |        | Liste des départements |
| DISCIPLINE 			|        |             |         |       |        | Liste des disciplines (sections CNU, disc. second degré, etc) |
| EMPLOYEUR 			|        |             |         |       |        | Liste des employeurs |
| ETABLISSEMENT 		| Oui    |             |         |       |        | Liste des établissements |
| PAYS 				    |        |             | Oui     |       |        | Liste des pays |
| STRUCTURE 			|        |             | Oui     |       |        | Liste des structures |
| VOIRIE 				|        |             | Oui     |       |        | Liste des voiries |
|  |
| <td colspan=3> _Données comptables_ |
| CENTRE_COUT 			|        |             |         | Oui   |        | Liste des centres de coûts |
| CENTRE_COUT_EP 		|        |             |         |       |        | Relation n <=> nentre les centres de coûts et les éléments pédagogiques |
| CENTRE_COUT_STRUCTURE |        |             |         |       | Oui    | Relation n <=> nentre les centres de coûts et les structures  |
| DOMAINE_FONCTIONNEL 	|        |             |         | Oui   |        | Liste des domaines fonctionnels |
|  |
| <td colspan=3>_Données décrivant l'offre de formation_ |
| CHEMIN_PEDAGOGIQUE 	| Oui    | Oui         |         |       |        | Relation n <=> n entre les étapes et les éléments pédagogiques |
| EFFECTIFS 			| Oui    |             |         |       |        | Effectifs étudiants par élément péagogique |
| EFFECTIFS_ETAPE 		|        |             |         |       |        | Effectifs étudiants par étape |
| ELEMENT_PEDAGOGIQUE 	| Oui    | Oui         |         |       |        | Liste des éléments pédagogiques |
| ELEMENT_TAUX_REGIMES 	|        |             |         |       |        | Taux FI/FC/FA par élément pédagogique |
| ETAPE 				| Oui    | Oui         |         |       |        | Liste des étapes |
| GROUPE_TYPE_FORMATION | Oui    |             |         |       |        | Liste des groupes de types de formation (License, Master, DU, etc.) |
| LIEN 					| Oui    |             |         |       |        | Liens entre deux noeuds |
| NOEUD 				| Oui    |             |         |       |        | Noeuds formant les arbres d'une formation, situés entre les étapes et les éléments pédagogiques |
| SCENARIO_LIEN 	    | Oui    |             |         |       |        | Paramétrage des liens |
| SCENARIO_NOEUD 	    | Oui    |             |         |       |        | Paramétrage des noeuds |
| TYPE_FORMATION 		| Oui    |             |         |       |        | Liste des types de formation |
|  |
<td colspan=5>_Données liées aux services d'enseignement_ |
| SERVICE 			    |        |             |         |       |        | Lignes de service intervenant (enseignement) |
| SERVICE_REFERENTIEL   |        |             |         |       |        | Lignes de service intervenant (référentiel) |
| TYPE_INTERVENTION_EP 	|        |             |         |       | Oui    | Relation n <=> n spécifiant quels types d'intervention sont pertinents par élément pédagogique |
| TYPE_MODULATEUR_EP 	|        |             |         |       | Oui    | Relation n <=> n spécifiant quels types de modulateurs sont pertinents par élément pédagogique |
| VOLUME_HORAIRE 		|        |             |         |       |        | Volumes horaires (grain fin de la saisie de service : heures d'enseignement) |
| VOLUME_HORAIRE_CHARGE |        |             |         |       |        | Table non exploitée : à ignorer |
| VOLUME_HORAIRE_ENS 	| Oui    |             |         |       |        | Charge d'enseingement |
| VOLUME_HORAIRE_REF    |        |             |         |       |        | Volumes horaires (grain fin de la saisie de service : heures de référentiel) |